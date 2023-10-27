<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/2
 * Time: 1:48 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\jobs;

use app\forms\OAuth;
use app\forms\open3rd\ExtAppForm;
use app\forms\open3rd\OpenErrorCode;
use app\helpers\CurlHelper;
use app\models\Mall;
use yii\queue\JobInterface;

class PublicExtApp extends BaseJob implements JobInterface
{
    /** @var Mall */
    public $mall;

    public $requestUrl;
    public $instanceId;
    public $uid;
    public $appId;

    public function execute($queue)
    {
        $this->setRequest();
        \Yii::$app->setMall($this->mall);
        try{
            $ext = ExtAppForm::instance();
            $list = $ext->templateList();
            $template = [];
            if (!empty($list->template_list)) {
                $temp = array_column($list->template_list, 'create_time');
                array_multisort($temp, SORT_DESC, $list->template_list);
                foreach ($list->template_list as $item) {
                    if($item->template_type == 0){ // 目前支持普通模板库 @czs
                        $template = $item;
                        break;
                    }
                }
            }
            if($template){
                $domain = \Yii::$app->hostInfo . \Yii::$app->baseUrl . "/shoproot.php";
                $domain = str_replace("platform-notify", "", $domain);
                $ext_json = json_encode([
                    'extEnable' => true,
                    'extAppid' => $ext->authorizer_appid,
                    'ext' => [
                        "siteroot" => "{$domain}",
                        "apiroot" => "{$domain}?_mall_id=".\Yii::$app->mall->id,
                        "domain" => \Yii::$app->hostInfo
                    ],
                    'plugins' => (object)[]
                ]);
                $url = "https://api.weixin.qq.com/wxa/commit?access_token=" . $ext->authorizer_access_token;
                $data = json_encode([
                    'template_id' => $template->template_id,
                    'ext_json' => $ext_json,
                    'user_version' => app_version(),
                    'user_desc' => \Yii::$app->mall->name
                ], JSON_UNESCAPED_UNICODE);
                $ret = json_decode($ext->getCurl()->post($url, $data)->response);
                if ($ret->errcode != 0) {
                    throw new \Exception(OpenErrorCode::errorMsg($ret->errcode, $ret->errmsg));
                }

                $url = parse_url(\Yii::$app->hostInfo);
                $serverDomain = [
                    'action' => 'set',
                    'requestdomain' => [
                        'https://' . $url['host']
                    ],
                    'wsrequestdomain' => [
                        'wss://' . $url['host']
                    ],
                    'uploaddomain' => [
                        'https://' . $url['host']
                    ],
                    'downloaddomain' => [
                        'https://' . $url['host']
                    ],
                ];
                $ext->setServerDomain(json_encode($serverDomain));

                if($this->requestUrl) {
                    $content = $ext->getExpVersion();
                    $experience_code = '';
                    if($content){
                        $name = md5(base64_encode($content)) . '.jpg';
                        $save_path = \Yii::$app->basePath . '/web/temp/' . $name;
                        if (!is_dir(\Yii::$app->basePath . '/web/temp')) {
                            mkdir(\Yii::$app->basePath . '/web/temp');
                        }
                        $fp = fopen($save_path, 'w');
                        fwrite($fp, $content);
                        fclose($fp);
                        $experience_code = \Yii::$app->hostInfo . \Yii::$app->baseUrl . "/temp/{$name}";
                    }
                    $params = [
                        'id' => $this->instanceId,
                        'appId' => $this->appId,
                        'uid' => $this->uid,
                        'experience_code' => $experience_code
                    ];
                    $params['experience_code'] = str_replace("platform-notify", "", $params['experience_code']);
                    $params['sign'] = OAuth::getSign($params);
                    $res = CurlHelper::getInstance()->httpPost($this->requestUrl, [], $params);
                    if ($res['code'] != 0) {
                        \Yii::error("请求接口报错：".$res['msg']);
                    }
                }
            }
        }catch (\Exception $e){
            \Yii::error('快速体验小程序发布代码失败');
            \Yii::error($e);
        }
    }
}
