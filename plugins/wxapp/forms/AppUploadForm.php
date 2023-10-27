<?php
/**
 * Created by IntelliJ IDEA.
 * User: opmall
 * Date: 2019/2/26
 * Time: 11:11
 */

namespace app\plugins\wxapp\forms;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\plugins\wxapp\models\WxappConfig;

class AppUploadForm extends Model
{
    public $action;
    public $branch;
    private $publisherDomain = "https://update.netbcloud.com/public/index.php"; // 发布机域名
//    private $publisherDomain = "http://127.0.0.1/updateService/public/index.php"; // 发布机域名 - 测试
    private $checkUpdateRoute = "/wxApp/checkVersion"; // 检查更新
    private $invokeCodeUrl = "/wxApp/getInvokeCode"; // 调用码
    private $previewUrl = "/wxApp/previewByMini"; // 预览码
    private $publishUrl = "/wxApp/publishByMini"; // 发布代码
    private $ipUrl = "/wxApp/getIp"; // 获取IP
    private $wxMiniProjectName = "微购儿新版商城";
    private $version = "1.0.0";
    private $cacheText = "WXAPP_UPLOAD_TOKEN_";

    public function rules()
    {
        return [
            ['action', 'required'],
            ['branch', 'safe'],
        ];
    }

    public function getResponse()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        try{
            switch ($this->action) {
                case 'preview':
                    return $this->preview();
                    break;
                case 'upload':
                    return $this->upload();
                    break;
                default:
                    break;
            }
        } catch (\Exception $exception) {
            \Yii::$app->cache->delete($this->cacheText.\Yii::$app->mall->id);
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $exception->getMessage()];
        }
    }

    public function getInvokeCode(){
        $wxAppConfig = $this->getWxApp();
        $currentVersion = CommonOption::get('wxapp_current_version', 0, 'plugin', $this->version);
        $result = $this->curl($this->publisherDomain.$this->checkUpdateRoute, json_encode([
            "version" => $currentVersion, "projectName" => $this->wxMiniProjectName, "appVersion" => app_version()
        ], JSON_UNESCAPED_UNICODE));
        $result = json_decode(trim($result, chr(239) . chr(187) . chr(191)), true);
        $isLocal = 1;
        if(isset($result["code"]) && $result["code"] == 0 && !empty($result['data'])) {
            $currentVersion = $result["data"]['version'];
            $isLocal = 0;
        }
        $pluginList = (array)CommonOption::get('wxapp_enable_plugins', \Yii::$app->mall->id, 'plugin', []);
        $plugins = count($pluginList) ? $pluginList : [];
        $requestParams = [
            "projectName" => $this->wxMiniProjectName, "version" => $currentVersion,
            "appId" => $wxAppConfig->appid, "isLocal" => $isLocal, "host" => \Yii::$app->request->hostInfo,
            "baseUrl" => \Yii::$app->request->scriptUrl."?_mall_id=".\Yii::$app->mall->id, "plugins" => $plugins
        ];
        $res = $this->curl($this->publisherDomain.$this->invokeCodeUrl, json_encode($requestParams));
        $res = json_decode(trim($res, chr(239) . chr(187) . chr(191)), true);
        if($res["code"] != 0){
            throw new \Exception("error，{$res["msg"]}");
        }
        \Yii::$app->cache->set($this->cacheText.$res["data"], [
            "certKey" => $wxAppConfig->wx_mini_upload_key, 'appId' => $wxAppConfig->appid, 'version' => $currentVersion,
            "desc" => !empty($result["data"]['remarks']) ? $result["data"]['remarks'] : $this->wxMiniProjectName
        ], 7200);
        return ["code" => ApiCode::CODE_SUCCESS, "data" => ['token' => $res["data"]]];
    }

    public function getIp(){
        $res = $this->curl($this->publisherDomain.$this->ipUrl, "");
        $res = json_decode(trim($res, chr(239) . chr(187) . chr(191)), true);
        return !empty($res['data']['ip']) ? $res['data']['ip'] : "";
    }

    public function preview()
    {
        $token = \Yii::$app->request->get("token");
        $cacheData = \Yii::$app->cache->get($this->cacheText.$token);
        $requestParams = [
            "projectName" => $this->wxMiniProjectName, "appId" => $cacheData["appId"], "token" => $token, 'certKey' => $cacheData["certKey"],
            "desc" => $cacheData["desc"]
        ];
        $res = $this->curl($this->publisherDomain.$this->previewUrl, json_encode($requestParams));
        $res = json_decode(trim($res, chr(239) . chr(187) . chr(191)), true);
        if($res["code"] != "0"){
            return ["code" => ApiCode::CODE_ERROR, "msg" => isset($res["msg"]) ? $res["msg"] : "获取预览结果失败"];
        }else {
            return ["code" => ApiCode::CODE_SUCCESS, "data" => ["qrcode" => $res["data"]['base64']]];
        }
    }

    public function upload()
    {
        $token = \Yii::$app->request->get("token");
        $cacheData = \Yii::$app->cache->get($this->cacheText.$token);
        $requestParams = [
            "projectName" => $this->wxMiniProjectName, "appId" => $cacheData["appId"], "token" => $token, 'certKey' => $cacheData["certKey"],
            "desc" => $cacheData["desc"], 'version' => $cacheData['version']
        ];
        $res = $this->curl($this->publisherDomain.$this->publishUrl, json_encode($requestParams));
        $res = json_decode(trim($res, chr(239) . chr(187) . chr(191)), true);
        if($res["code"] != "0"){
            return ["code" => ApiCode::CODE_ERROR, "msg" => isset($res["msg"]) ? $res["msg"] : "获取上传结果失败"];
        }else {
            CommonOption::set('wxapp_current_version', $cacheData['version'], 0, 'plugin');
        }
        $res["data"] = ['version' => "V{$cacheData['version']}", 'desc' => $cacheData["desc"]];
        \Yii::$app->cache->delete($this->cacheText.$token);
        return ["code" => ApiCode::CODE_SUCCESS, "msg" => $res["msg"], "data" => $res["data"]];
    }

    public function curl($url, $data = "", $header = ['Content-Type: application/json'], $timeout = 1200)
    {
        $ch = curl_init($url);
        if(empty($data)){
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }else {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        if(!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $output = curl_exec($ch);
        $errno = curl_errno($ch);
        if ($errno) {
            return false;
        }
        curl_close($ch);
        return $output;
    }

    private function getWxApp()
    {
        $wxappConfig = WxappConfig::findOne(['mall_id' => \Yii::$app->mall->id]);
        if (!$wxappConfig) {
            throw new \Exception('小程序信息尚未配置。');
        }
        if (!$wxappConfig->appid) {
            throw new \Exception('小程序AppId尚未配置。');
        }
        if (!$wxappConfig->wx_mini_upload_key) {
            throw new \Exception('小程序上传密钥尚未配置。');
        }
        return $wxappConfig;
    }
}