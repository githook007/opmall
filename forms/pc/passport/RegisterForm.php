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
use app\events\UserEvent;
use app\forms\api\LoginUserInfo;
use app\forms\common\share\CommonShare;
use app\models\Model;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;
use app\models\UserPlatform;

class RegisterForm extends Model
{
    public $mobile;
    public $password;

    public $user_info;
    public $parent_id = 0;

    public function rules()
    {
        return [
            [['mobile', 'password'], 'required'],
            [["user_info"], "safe"],
            [["parent_id"], "integer"],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
        ];
    }

    public function getUserPlatform($userInfo)
    {
        $userPlatform = UserPlatform::findOne([
            'platform' => $userInfo->user_platform,
            'platform_id' => $userInfo->user_platform_user_id,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if (!$userPlatform) {
            $user = User::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'mobile' => $userInfo->username,
                'is_delete' => 0,
            ]);
        } else {
            $user = $userPlatform->user;
            if (!$user) {
                $userPlatform->mall_id = -$userPlatform->mall_id;
                $userPlatform->save();
                $user = $this->getUserPlatform($userInfo);
            }
        }
        return $user;
    }

    /**
     * @return LoginUserInfo
     * @throws \Exception
     */
    public function getWxUserInfo()
    {
        $userInfo = new LoginUserInfo();
        $userInfo->username = $this->user_info['openId'];
        $userInfo->scope = 'auth_info';
        $userInfo->nickname = $this->user_info['nickName'] ?? "新用户" . substr($this->mobile, -4);
        $userInfo->avatar = $this->user_info['avatarUrl'] ?? '';
        $userInfo->user_platform_user_id = $this->user_info['openId'];
        $userInfo->user_platform = UserInfo::PLATFORM_WXAPP;
        $userInfo->unionId = $this->user_info['unionId'] ?? '';
        $userInfo->password = \Yii::$app->security->generatePasswordHash($this->password);
        return $userInfo;
    }

    public function startRegister(){
        $t = \Yii::$app->db->beginTransaction();
        try {
            $userInfo = $this->getWxUserInfo();
            $user = $this->getUserPlatform($userInfo);
            $register = false;
            if(!$user){
                $user = new User();
                $user->mall_id = \Yii::$app->mall->id;
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->auth_key = \Yii::$app->security->generateRandomString();
                $user->username = $userInfo->username ?: $this->mobile;
                $user->nickname = $userInfo->nickname;
                $user->mobile = $this->mobile;
                $user->password = $userInfo->password;
                if (!$user->save()) {
                    throw new \Exception($this->getErrorMsg($user));
                }
                $userPlatform = new UserPlatform();
                $userPlatform->mall_id = $user->mall_id;
                $userPlatform->user_id = $user->id;
                $userPlatform->platform = $userInfo->user_platform;
                $userPlatform->platform_id = $userInfo->user_platform_user_id;
                $userPlatform->unionid = $userInfo->unionId;
                $userPlatform->password = $user->password;
                $userPlatform->subscribe = $userInfo->subscribe;
                if (!$userPlatform->save()) {
                    throw new \Exception($this->getErrorMsg($userPlatform));
                }
                $register = true;
            }else{
                if($user->mobile && $user->mobile != $this->mobile){
                    throw new \Exception('微信已经绑定了其它手机号');
                }
            }
            if(!$user->userInfo) {
                // 用户信息表
                $uInfo = new UserInfo();
                $uInfo->user_id = $user->id;
                $uInfo->platform_user_id = $userInfo->username ?: $this->mobile;
                //$uInfo->source = "pc";
                $uInfo->avatar = $userInfo->avatar;
                $uInfo->platform = UserInfo::PLATFORM_WXAPP; // 默认小程序
                $uInfo->is_delete = 0;
                if (!$uInfo->save()) {
                    throw new \Exception($this->getErrorMsg($uInfo));
                }
            }
            if(!$user->identity) {
                // 用户角色表
                $userIdentity = new UserIdentity();
                $userIdentity->user_id = $user->id;
                if (!$userIdentity->save()) {
                    throw new \Exception($this->getErrorMsg($userIdentity));
                }
            }

            // 用户平台信息表
            $userPlatform = UserPlatform::findOne([
                'platform_id' => $this->mobile,
                'platform' => UserInfo::PLATFORM_PC,
            ]);
            if (!$userPlatform) {
                $userPlatform = new UserPlatform();
                $userPlatform->platform = UserInfo::PLATFORM_PC;
                $userPlatform->platform_id = $this->mobile;
            }
            $userPlatform->user_id = $user->id;
            $userPlatform->mall_id = $user->mall_id;
            $userPlatform->unionid = $userInfo->unionId;
            $userPlatform->password = $userInfo->password;
            $userPlatform->subscribe = $userInfo->subscribe;
            if (!$userPlatform->save()) {
                throw new \Exception($this->getErrorMsg($userPlatform));
            }

            $t->commit();

            if($register) {
                $event = new UserEvent();
                $event->sender = $this;
                $event->user = $user;
                \Yii::$app->trigger(User::EVENT_REGISTER, $event);
            }

            if ($this->parent_id) {
                $common = CommonShare::getCommon();
                $common->mall = \Yii::$app->mall;
                $common->user = $user;
                try {
                    $common->bindParent($this->parent_id, 1);
                } catch (\Exception $exception) {
                    \Yii::error("pc端成為下級錯誤：".$exception->getMessage());
                    $userInfo = $common->user->userInfo;
                    $userInfo->temp_parent_id = $this->parent_id;
                    $userInfo->save();
                }
            }
        }catch (\Exception $e){
            $t->rollBack();
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $e->getMessage(),];
        }
        return ['code' => ApiCode::CODE_SUCCESS, "msg" => "注册成功"];
    }
}
