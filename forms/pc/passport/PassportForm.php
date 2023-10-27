<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\passport;

use app\bootstrap\response\ApiCode;
use app\bootstrap\sms\Sms;
use app\events\UserEvent;
use app\models\Model;
use app\models\pc\UserLogin;
use app\models\User;
use app\models\UserInfo;
use app\models\UserPlatform;

class PassportForm extends Model
{
    public $mobile;
    public $password;
    public $confirm_password;
    public $sms_code;
    public $user_info;
    public $parent_id = 0;
//    public $pic_captcha;

    public function rules()
    {
        return [
            [['mobile', 'password'], 'required'],
            [["sms_code", "confirm_password", "user_info"], "string"],
            [["parent_id"], "integer"],
//            [['pic_captcha'], 'captcha', 'captchaAction' => 'site/pic-captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
//            'pic_captcha' => '验证码',
        ];
    }

    public function mobileLogin()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $userPlatform = $this->getUser($this->mobile, UserInfo::PLATFORM_PC);
        if(!$userPlatform || !$userPlatform->user){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '手机号用户不存在'];
        }

        if(!\Yii::$app->security->validatePassword($this->password, $userPlatform->password)){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => "密码不正确"
            ];
        }

        $model = new UserLogin();
        $model->mall_id = \Yii::$app->mall->id;
        $model->user_id = $userPlatform->user->id;
        $model->ip = \Yii::$app->request->getUserIP();
        $model->expire_time = time() + 86400;
        $model->token = \Yii::$app->security->generateRandomString();
        if(!$model->save()){
            return $this->getErrorMsg($model);
        }

        $event = new UserEvent();
        $event->sender = $this;
        $event->user = $userPlatform->user;
        \Yii::$app->trigger(User::EVENT_LOGIN, $event);
        \Yii::$app->user->login($userPlatform->user, 86400);
        return [
            'code' => 0,
            'data' => [
                'access_token' => $model->token,
            ],
            "msg" => "登录成功"
        ];
    }

    public function mobileRegister(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if(empty($this->sms_code)){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => "短信验证码不能为空"];
        }
        if($this->confirm_password != $this->password){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => "确认密码不正确"];
        }
        $message = Sms::checkValidateCode($this->mobile, $this->sms_code);
        if (!$message) {
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '验证码不正确'];
        }
        $userPlatform = $this->getUser($this->mobile, UserInfo::PLATFORM_PC);
        if($userPlatform){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '手机号已注册'];
        }
        $userId = \Yii::$app->request->post("p_user_id", 0);
        $form = new WxAppForm();
        $form->type = 2;
        $form->data = \Yii::$app->serializer->encode(["mobile" => $this->mobile, "password" => $this->password, "parent_id" => $userId]);
        $res = $form->qrCode();
        if($res['code'] === 0){
            Sms::updateCodeStatus($this->mobile, $this->sms_code);
        }
        return $res;
    }

    /**
     * @param $mobile
     * @param $platform
     * @return array|\yii\db\ActiveRecord|null|UserPlatform
     */
    public function getUser($mobile, $platform)
    {
        return UserPlatform::findOne([
            'platform' => $platform, 'platform_id' => $mobile, 'mall_id' => \Yii::$app->mall->id
        ]);
    }

    // 忘记密码
    public function forgetPassword(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if(empty($this->sms_code)){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => "短信验证码不能为空"
            ];
        }
        if($this->confirm_password != $this->password){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => "确认密码不正确"
            ];
        }
        $message = Sms::checkValidateCode($this->mobile, $this->sms_code);
        if (!$message) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '验证码不正确',
            ];
        }
        $t = \Yii::$app->db->beginTransaction();
        $userPlatform = $this->getUser($this->mobile, UserInfo::PLATFORM_PC);
        if(!$userPlatform || $userPlatform->user){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '手机号未注册'];
        }

        $userPlatform->password = \Yii::$app->security->generatePasswordHash($this->password);
        if (!$userPlatform->save()) {
            $t->rollBack();
            return $this->getErrorResponse($userPlatform);
        }
        $t->commit();
        return [
            'code' => 0,
            "msg" => "密码修改成功"
        ];
    }

    public function logout(){
        \Yii::$app->user->logout();
        UserLogin::updateAll(["expire_time" => time()], ["mall_id" => \Yii::$app->mall->id, "user_id" => \Yii::$app->user->id]);
        return [
            'code' => 0,
            "msg" => "退出成功"
        ];
    }
}
