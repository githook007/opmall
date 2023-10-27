<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\passport;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\pc\UserLogin;
use app\models\pc\UserRegister;
use GuzzleHttp\Client;

class WxAppForm extends Model
{
    public $listen;
    public $type = 1; // 1 : 登录；2 ： 注册
    public $data;

    public function rules()
    {
        return [
            [["listen", "data"], "string"],
            [["type"], "integer"]
        ];
    }

    // 二维码
    public function qrCode(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $accessToken = \Yii::$app->getWechat()->getAccessToken();
            $api = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$accessToken}";
            $client = new Client();
            $token = \Yii::$app->security->generateRandomString();
            $data = \Yii::$app->serializer->encode(['path' => 'pages/auth_web/login?token='.$token."&type={$this->type}", 'width' => 1280]);
            $response = $client->post($api, ['verify' => false, 'body' => $data]);
            $contentTypes = $response->getHeader('Content-Type');
            $body = $response->getBody();
            foreach ($contentTypes as $contentType) {
                if (mb_stripos($contentType, 'image') !== false) {
                    $imgName = md5(strtotime('now')) . '.jpg';
                    // 获取图片存储的路径
                    $res = file_uri('/web/temp/');
                    $localUri = $res['local_uri'];
                    $webUri = $res['web_uri'];

                    if($this->type === 1) { // 登录用的
                        UserLogin::find()->createCommand()->setSql("DELETE from " . UserLogin::tableName() . " where `expire_time` < " . time() . " AND user_id <= 0")->execute();
                        if (file_put_contents($localUri . $imgName, $body) !== false) {
                            $model = new UserLogin();
                            $model->mall_id = \Yii::$app->mall->id;
                            $model->user_id = 0;
                            $model->ip = \Yii::$app->request->getUserIP();
                            $model->expire_time = time() + 86400;
                            $model->token = $token;
                            if (!$model->save()) {
                                return $this->getErrorMsg($model);
                            }
                        } else {
                            return ['code' => ApiCode::CODE_ERROR, 'msg' => '文件写入失败，请检查目录是否有写入权限。'];
                        }
                    }else{
                        UserRegister::find()->createCommand()->setSql("DELETE from " . UserRegister::tableName() . " where `expire_time` < " . time())->execute();
                        if (file_put_contents($localUri . $imgName, $body) !== false) {
                            $model = new UserRegister();
                            $model->mall_id = \Yii::$app->mall->id;
                            $model->expire_time = time() + 86400;
                            $model->data = $this->data;
                            $model->status = 0;
                            $model->token = $token;
                            if (!$model->save()) {
                                return $this->getErrorMsg($model);
                            }
                        } else {
                            return ['code' => ApiCode::CODE_ERROR, 'msg' => '文件写入失败，请检查目录是否有写入权限。'];
                        }
                    }
//                    $pic = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . '?r=pc/web/index/qr-code&url=' . urlencode($model->token."&type={$this->type}")."&mall_id=".base64_encode(\Yii::$app->mall->id);
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => ['qrcode' => $webUri . $imgName, "listen" => $model->token, "type" => $this->type]//, "pic" => $pic],
                    ];
                }
            }
            $result = \Yii::$app->serializer->decode((string)$body);
            if (isset($result['errcode']) && $result['errcode'] != 0) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => 'errcode: ' . $result['errcode'] . ', errmsg: ' . (isset($result['errmsg']) ? $result['errmsg'] : ''),
                ];
            } else {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '未知错误: ' . ((string)$body)];
            }
        } catch (\Exception $exception) {
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $exception->getMessage()];
        }
    }

    public function listenRes(){
        if(empty($this->listen)){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => 'listen不存在'];
        }
        if($this->type == '1') {
            $data = UserLogin::findOne(["mall_id" => \Yii::$app->mall->id, "token" => $this->listen]);
            if (!$data || $data->expire_time <= time()) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '扫码登录过期了'];
            }
            if ($data->user_id > 0) {
                return ['code' => ApiCode::CODE_SUCCESS, 'data' => ['access_token' => $data->token,], "msg" => "登录成功"];
            } else {
                if ($data->user_id == '-1') {
                    return ['code' => 3, "msg" => "前往注册"
                    ];
                } else {
                    return ['code' => 2, "msg" => "监听登录中"];
                }
            }
        }else{
            $data = UserRegister::findOne(["mall_id" => \Yii::$app->mall->id, "token" => $this->listen]);
            if (!$data || $data->expire_time <= time()) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '扫码登录过期了'];
            }
            if($data->status === 0){
                return ['code' => 2, "msg" => "监听中"];
            }
            $params = json_decode($data->data, true);
            $form = new RegisterForm();
            $form->attributes = $params;
            return $form->startRegister();
        }
    }
}
