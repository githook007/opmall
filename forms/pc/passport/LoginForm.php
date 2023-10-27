<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/5 12:09
 */


namespace app\forms\pc\passport;

use app\bootstrap\response\ApiCode;
use app\forms\api\LoginUserInfo;
use app\models\pc\UserLogin;
use app\models\User;
use app\models\UserInfo;

class LoginForm extends \app\forms\api\LoginForm
{
    public $rawData;
    public $encryptedData;
    public $iv;
    public $code;
    public $token;
    public $type;

    public function rules()
    {
        return [
            [['token', 'encryptedData', 'iv', 'code'], 'required'],
            [['rawData', 'encryptedData', 'iv', 'code', 'token', 'type'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'token' => 'token',
        ];
    }

    public function login() {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $model = UserLogin::findOne(["token" => $this->token, "mall_id" => \Yii::$app->mall->id]);
            if (!$model) {
                throw new \Exception("错误，登录信息不存在");
            }
            $res = parent::login();
            if($res['code'] == ApiCode::CODE_ERROR){
                throw new \Exception($res['msg']);
            }
            $user = User::findOne([
                'access_token' => $res['data']['access_token'],
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
            ]);
            $model->user_id = $user->id;
            if (!$model->save()) {
                return ["code" => ApiCode::CODE_ERROR, "msg" => current($model->getFirstErrors())];
            }
            return [
                "code" => ApiCode::CODE_SUCCESS, "msg" => "登录成功"
            ];
        } catch (\Exception $exception) {
            return ["code" => ApiCode::CODE_ERROR, "msg" => $exception->getMessage()];
        }
    }

    /**
     * @return LoginUserInfo
     * @throws \Exception
     */
    public function getUserInfo()
    {
        $postUserInfo = $this->rawData ? json_decode($this->rawData, true) : [
            'nickName' => '',
            'avatarUrl' => '',
        ];
        $data = \Yii::$app->getWechat()->decryptData($this->encryptedData, $this->iv, $this->code);
        $openId = $data['openId'];
        $unionId = $data['unionId'];

        $userInfo = new LoginUserInfo();
        $userInfo->username = $openId;
        $userInfo->scope = 'auth_info';
        $userInfo->nickname = $data['nickName'] ?? $postUserInfo['nickName'];
        $userInfo->avatar = $data['avatarUrl'] ?? $postUserInfo['avatarUrl'];
        $userInfo->platform_user_id = $openId;
        $userInfo->platform = UserInfo::PLATFORM_WXAPP;
        $userInfo->unionId = $unionId;
        return $userInfo;
    }
}
