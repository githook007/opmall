<?php
/**
 * 获取微信第三方访问量
 */
namespace app\forms\open3rd;

use Curl\Curl;

/**
 * Class ExtAppForm
 * @package app\forms\open3rd
 */
class WxRecode
{
    // 获取访问量
    public function getRecode(){
        $url = 'https://api.weixin.qq.com/wxa/getwxacode';  // 获取用户访问小程序日留存
        try {
            $accTokens = \Yii::$app->getWechat();
            $appid = $accTokens->appId;
            $accToken = $accTokens->getAccessToken();
        } catch (\Exception $e) {
            $accToken = false;
        }
        if($accToken){
            $imageFilePath = \Yii::$app->basePath .'/web/wxRecode/'; //图片本地存储的路径
            if (!is_file($imageFilePath . $appid . '.png')){
                $param = [
                    'path' => 'pages/index/index'
                ];
                $ret = $this->getCurl()->post($url.'?access_token='.$accToken, json_encode($param, JSON_FORCE_OBJECT))->response;
                $res = json_decode($ret);
                if(empty($res['errcode'])){
                    if (!file_exists($imageFilePath)) {
                        mkdir($imageFilePath, 0777, true);
                    }
                    $file = fopen($imageFilePath . $appid . '.png', "w");//打开文件准备写入
                    fwrite($file, $ret);//写入
                    fclose($file);//关闭
                    $data = ['code'=>0, 'url' => \Yii::$app->request->hostInfo . '/web/wxRecode/' . $appid . '.png', 'msg'=>'success'];
                }else{
                    $data = ['code'=>1, 'msg'=>'生成二维码失败'.$res['errmsg']];
                }
            }else{
                $data = ['code'=>0, 'url' => \Yii::$app->request->hostInfo . '/web/wxRecode/' . $appid . '.png', 'msg'=>'success'];
            }
        }else{
            $data = ['code'=>1, 'msg'=>'error'];
        }
        return $data;
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $curl;
    }
}