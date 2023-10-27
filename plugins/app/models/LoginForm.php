<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/9
 * Time: 4:17 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\models;

use app\bootstrap\response\ApiCode;
use app\forms\api\LoginUserInfo;
use app\models\UserInfo;
use app\models\UserPlatform;
use app\plugins\app\forms\api\RegisterForm;
use app\plugins\app\forms\common\CommonSetting;
use app\plugins\wechat\forms\common\wechat\WechatConfig;
use app\validators\ValidateCodeValidator;

class LoginForm extends \app\forms\api\LoginForm
{
    public $password;
    public $mobile;
    public $email;
    public $sms_captcha;
    public $type;
    public $code;
    public $validate_code_id;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['username'] = ['mobile', 'password'];
        $scenarios['mobile'] = ['mobile', 'sms_captcha', 'validate_code_id'];
        $scenarios['email'] = ['email', 'code', 'validate_code_id'];
        $scenarios['wechat'] = ['code'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['type'], 'required'],
            [['password'], 'required', 'on' => ['username']],
            [['mobile', 'sms_captcha', 'validate_code_id'], 'required', 'on' => ['mobile']],
            [['email', 'code', 'validate_code_id'], 'required', 'on' => ['email']],
            [['code'], 'required', 'on' => ['wechat']],
            [['sms_captcha'], ValidateCodeValidator::class,
                'mobileAttribute' => 'mobile',
                'validateCodeIdAttribute' => 'validate_code_id',
                'on' => ['mobile']
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
            'password' => '密码',
            'mobile' => '手机号',
            'email' => '邮箱',
            'code' => '邮箱验证码',
            'sms_captcha' => '短信验证码',
            'type' => '登录类型',
            'validate_code_id' => '验证码id',
        ];
    }

    public function getUserInfo()
    {
        $this->scenario = \Yii::$app->request->post('type');
        $this->attributes = \Yii::$app->request->get();
        $this->attributes = \Yii::$app->request->post();
        if (!$this->validate()) {
            throw new \Exception($this->getErrorMsg());
        }
        switch ($this->scenario) {
            case 'username':  // 账号密码
                $userInfo = $this->usernameLogin();
                break;
            case 'mobile':  // 手机号
                $userInfo = $this->mobileLogin();
                break;
            case 'email':  // 邮箱
                $userInfo = $this->emailLogin();
                break;
            case 'wechat':  // 微信授权
                $userInfo = $this->wechatLogin();
                break;
            default:
                throw new \Exception('未知的登录方式');
        }
        return $userInfo;
    }

    /**
     * @throws \Exception
     * 账号密码登录
     */
    private function usernameLogin()
    {
        $userPlatform = $this->getUser($this->mobile);
        if (!$userPlatform) {
            throw new \Exception('用户不存在，请先注册');
        }

        if (!\Yii::$app->getSecurity()->validatePassword($this->password, $userPlatform->password)) {
            throw new \Exception('密码错误');
        }
        $userInfo = new LoginUserInfo();
        $userInfo->username = $userPlatform->platform_id;
        $userInfo->scope = 'auth_base';
        $userInfo->platform = $userPlatform->platform;
        $userInfo->platform_user_id = $userPlatform->platform_id;
        $userInfo->user_platform = $userPlatform->platform;
        $userInfo->user_platform_user_id = $userPlatform->platform_id;
        return $userInfo;
    }

    private function mobileLogin()
    {
        $userPlatform = $this->getUser($this->mobile);
        if (!$userPlatform) {
            // czs 注册用户并登录
            $t = \Yii::$app->db->beginTransaction();
            try {
                $form = new RegisterForm();
                $form->mobile = $this->mobile;
                $user = $form->registerUser();
                $t->commit();
                $form->triggerEvent($user);
                return $this->mobileLogin();
            }catch (\Exception $e){
                $t->rollBack();
            }
        }
        $userInfo = new LoginUserInfo();
        $userInfo->username = $userPlatform->platform_id;
        $userInfo->scope = 'auth_base';
        $userInfo->platform = $userPlatform->platform;
        $userInfo->platform_user_id = $userPlatform->platform_id;
        $userInfo->user_platform = $userPlatform->platform;
        $userInfo->user_platform_user_id = $userPlatform->platform_id;
        return $userInfo;
    }

    private function emailLogin(){
        $userPlatform = $this->getUser($this->email);
        if (!$userPlatform) {
            // czs 注册用户并登录
            $t = \Yii::$app->db->beginTransaction();
            try {
                $form = new RegisterForm();
                $form->email = $this->email;
                $user = $form->registerUser();
                $t->commit();
                $form->triggerEvent($user);
                return $this->emailLogin();
            }catch (\Exception $e){
                $t->rollBack();
            }
        }
        $userInfo = new LoginUserInfo();
        $userInfo->username = $userPlatform->platform_id;
        $userInfo->scope = 'auth_base';
        $userInfo->platform = $userPlatform->platform;
        $userInfo->platform_user_id = $userPlatform->platform_id;
        $userInfo->user_platform = $userPlatform->platform;
        $userInfo->user_platform_user_id = $userPlatform->platform_id;
        return $userInfo;
    }

    private function wechatLogin(){
        $wechatConfig = new WechatConfig();
        $wechat = CommonSetting::getCommon()->getSetting(CommonSetting::APP_WX_CONFIG);
        $res = $wechatConfig->getAccessToken([
            'appid' => $wechat['app_id'],
            'secret' => $wechat['app_secret'],
            'code' => $this->code
        ]);
        if (!empty($res['openid'])){
            $userInfoRes = $wechatConfig->getUserInfo([
                'access_token' => $res['access_token'],
                'openid' => $res['openid']
            ]);
            \Yii::warning('app微信登录：');
            if (!empty($userInfoRes['nickname']) && !empty($userInfoRes['openid'])){
                $userInfo = new LoginUserInfo();
                $userInfo->username = $userInfoRes['openid'];
                $userInfo->nickname = $userInfoRes['nickname'];
                $userInfo->platform = UserInfo::PLATFORM_APP;
                $userInfo->platform_user_id = $userInfoRes['openid'];
                $userInfo->avatar = $userInfoRes['headimgurl'];
                $userInfo->unionId = $userInfoRes['unionid'];
                return $userInfo;
            }else{
                throw new \Exception(json_decode($userInfoRes)->msg);
            }
        }else{
            throw new \Exception(json_decode($res)->msg);
        }
    }

    public function wxConfig(){
        $wechat = CommonSetting::getCommon()->getSetting(CommonSetting::APP_WX_CONFIG);
        return [
            "code" => ApiCode::CODE_SUCCESS,
            'data' => ['appid' => $wechat['app_id'], 'url' => \Yii::$app->request->hostInfo.'/']
        ];
    }

    /**
     * @param $mobile
     * @param $platform
     * @return array|\yii\db\ActiveRecord|null|UserPlatform
     */
    public function getUser($mobile, $platform = UserInfo::PLATFORM_APP)
    {
        return UserPlatform::findOne([
            'platform' => $platform, 'platform_id' => $mobile, 'mall_id' => \Yii::$app->mall->id
        ]);
    }
}
