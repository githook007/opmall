<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/14
 * Time: 4:11 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\api;

use app\bootstrap\response\ApiCode;
use app\events\UserEvent;
use app\forms\common\message\MessageService;
use app\forms\common\platform\PlatformConfig;
use app\models\Model;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;
use app\models\UserPlatform;
use app\plugins\app\models\LoginForm;
use app\validators\ValidateCodeValidator;

class RegisterForm extends Model
{
    public $mobile;
    public $sms_captcha;
    public $email;
    public $code;
    public $validate_code_id;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['sms_captcha', 'mobile', 'validate_code_id'];
        $scenarios['email'] = ['email', 'code', 'validate_code_id'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['sms_captcha', 'mobile'], 'required', 'on' => ['register']],
            [['validate_code_id'], 'required', 'on' => ['register'], 'message' => '请先发送验证码'],

            [['code', 'email'], 'required', 'on' => ['email']],
            [['validate_code_id'], 'required', 'on' => ['email'], 'message' => '请先发送验证码'],
            [['sms_captcha'], ValidateCodeValidator::class,
                'mobileAttribute' => 'mobile',
                'validateCodeIdAttribute' => 'validate_code_id',
                'on' => ['register']
            ],
            [['code'], ValidateCodeValidator::class,
                'mobileAttribute' => 'email',
                'validateCodeIdAttribute' => 'validate_code_id',
                'on' => ['email']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'email' => '邮箱',
            'sms_captcha' => '手机验证码',
            'code' => '邮箱验证码',
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            throw new \Exception($this->getErrorMsg());
        }

        $t = \Yii::$app->db->beginTransaction();
        try {
            $loginModel = new LoginForm();
            if ($this->mobile){
                $user = $loginModel->getUser($this->mobile);
                if ($user) {
                    throw new \Exception('该手机号已经注册，请直接登录');
                }
            }else{
                $user = $loginModel->getUser($this->email);
                if ($user) {
                    throw new \Exception('该邮箱号已经注册，请直接登录');
                }
            }

            $user = $this->registerUser();

            $t->commit();
            $this->triggerEvent($user);

            return [
                "code" => ApiCode::CODE_SUCCESS,
                'msg' => '注册成功',
                "data" => ['access_token' => $user->access_token]
            ];
        } catch (\Exception $exception) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }

    public function registerUser(){
        $query = User::find()->where([
            "mall_id" => \Yii::$app->mall->id,
            "mch_id" => 0,
            'is_delete' => 0
        ]);
        $account = '';
        if($this->mobile){
            $account = $this->mobile;
            $query->andWhere(['mobile' => $this->mobile]);
        }elseif($this->email){
            $account = $this->email;
            $query->andWhere(['email' => $this->email]);
        }
        $password = \Yii::$app->security->generateRandomString();

        $user = $query->one();
        if(!$user) {
            $user = new User();
            $user->mall_id = \Yii::$app->mall->id;
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->unionid = '';
            $user->password = \Yii::$app->security->generatePasswordHash($password, 6);
            $user->username = $account;
            $user->nickname = $account;
            $user->mobile = $this->mobile ?: '';
            $user->email = $this->email ?: '';
            if (!$user->save()) {
                throw new \Exception($this->getErrorMsg($user));
            }
        }

        if(!$user->userInfo){
            $uInfo = new UserInfo();
            $uInfo->user_id = $user->id;
            $uInfo->avatar = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
                '/statics/img/app/user-default-avatar.png';
            $uInfo->platform_user_id = $account;
            $uInfo->platform = UserInfo::PLATFORM_APP;
            $uInfo->is_delete = 0;
            $uInfo->junior_at = "0000-00-00 00:00:00";
            if (!$uInfo->save()) {
                throw new \Exception($this->getErrorMsg($uInfo));
            }
        }

        if(!$user->identity) {
            $userIdentity = new UserIdentity();
            $userIdentity->user_id = $user->id;
            if (!$userIdentity->save()) {
                throw new \Exception($this->getErrorMsg($userIdentity));
            }
        }

        $userPlatform = new UserPlatform();
        $userPlatform->mall_id = $user->mall_id;
        $userPlatform->user_id = $user->id;
        $userPlatform->platform = UserInfo::PLATFORM_APP;
        $userPlatform->platform_id = $account;
        $userPlatform->unionid = '';
        $userPlatform->password = $user->password;
        if (!$userPlatform->save()) {
            throw new \Exception($this->getErrorMsg($userPlatform));
        }
        $this->sendSmsToUser($user, $password);

        return $user;
    }

    public function triggerEvent($user){
        $event = new UserEvent();
        $event->sender = $this;
        $event->user = $user;
        \Yii::$app->trigger(User::EVENT_REGISTER, $event);
    }

    public function sendSmsToUser($user, $password)
    {
        try {
            \Yii::warning('----消息发送提醒----');
            if (!$user->mobile) {
                \Yii::warning('----用户未绑定手机号无法发送----');
                return;
            }
            $messageService = new MessageService();
            $messageService->user = $user;
            $messageService->content = [
                'mch_id' => 0,
                'args' => [$password]
            ];
            $messageService->platform = PlatformConfig::getInstance()->getPlatform($user);
            $messageService->tplKey = 'password';
            $messageService->templateSend();
        } catch (\Exception $exception) {
            \Yii::error('向用户发送短信消息失败');
            \Yii::error($exception);
        }
    }
}
