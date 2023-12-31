<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/5 15:00
 */


namespace app\forms\api;


use app\bootstrap\response\ApiCode;
use app\events\UserEvent;
use app\models\Model;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;
use app\models\UserPlatform;
use yii\helpers\Json;

abstract class LoginForm extends Model
{
    /**
     * @return LoginUserInfo
     */
    abstract protected function getUserInfo();

    public function login()
    {
        try {
            $userInfo = $this->getUserInfo();
            $userInfo->user_platform = $userInfo->user_platform ?: $userInfo->platform;
            $userInfo->user_platform_user_id = $userInfo->user_platform_user_id ?: $userInfo->platform_user_id;
        } catch (\Exception $exception) {
            \Yii::warning($exception);
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
        $user = $this->getUserPlatform($userInfo);
        \Yii::warning(Json::encode($userInfo, JSON_UNESCAPED_UNICODE));
        if ($userInfo->scope == 'auth_base') {
            if ($user) {
                $this->triggerEvent($user, false);
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => [
                        'access_token' => $user->access_token,
                        'update_info' => strpos($user->nickname, "微信用户") === false ? 1 : 0
                    ],
                ];
            } else {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'data' => [
                        'access_token' => null,
                        'update_info' => 1,
                    ],
                ];
            }
        }
        $t = \Yii::$app->db->beginTransaction();
        $register = false;
        // 微信小程序和微信公众平台数据以unionid进行互通
        if (
            in_array($userInfo->platform, [UserInfo::PLATFORM_WXAPP, UserInfo::PLATFORM_WECHAT, UserInfo::PLATFORM_APP])
            && !$user
            && $userInfo->unionId
        ) {
            $user = User::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'unionid' => $userInfo->unionId,
                'is_delete' => 0,
            ]);
        }
        if (!$user) {
            $register = true;
            $user = new User();
            $user->mall_id = \Yii::$app->mall->id;
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->username = $userInfo->username;
            $user->password = \Yii::$app->security
                ->generatePasswordHash(\Yii::$app->security->generateRandomString(), 5);
        }else{
            // 一天只更新一次token
            if(date("Y-m-d 00:00:00", strtotime($user->updated_at)) < date("Y-m-d 00:00:00", time())){
                $user->access_token = \Yii::$app->security->generateRandomString();
            }
        }
        $user->unionid = $userInfo->unionId;
        if($userInfo->nickname != "微信用户" || $register){
            $user->nickname = $userInfo->nickname;
        }else{
            $userInfo->avatar = '';
        }
        if (!$user->save()) {
            $t->rollBack();
            return $this->getErrorResponse($user);
        }
        if($user->nickname == '微信用户'){
            $user->nickname = $user->nickname . "_" . str_pad($user->id, 4, 0, STR_PAD_LEFT);
            if (!$user->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            }
        }
        if ($user->username != $userInfo->username && $userInfo->user_platform == UserInfo::PLATFORM_WECHAT) {
            // 公众号登录时，默认添加一条数据信息
            $userPlatform = UserPlatform::findOne([
                'user_id' => $user->id,
                'platform_id' => $user->username,
            ]);
            if (!$userPlatform) {
                $userPlatform = new UserPlatform();
                $userPlatform->mall_id = $user->mall_id;
                $userPlatform->user_id = $user->id;
                $userPlatform->platform = UserInfo::PLATFORM_WXAPP;
                $userPlatform->platform_id = $user->username;
                $userPlatform->unionid = $userInfo->unionId;
                $userPlatform->password = $userInfo->password;
                $userPlatform->subscribe = 0;
                if (!$userPlatform->save()) {
                    $t->rollBack();
                    return $this->getErrorResponse($userPlatform);
                }
            }
        }

        // 用户信息表
        $uInfo = UserInfo::findOne([
            'user_id' => $user->id,
            'is_delete' => 0,
        ]);
        if (!$uInfo) {
            $uInfo = new UserInfo();
            $uInfo->user_id = $user->id;
            $uInfo->avatar = $userInfo->avatar;
            $uInfo->platform_user_id = $userInfo->platform_user_id;
            $uInfo->platform = $userInfo->platform;
            $uInfo->junior_at = '0000-00-00 00:00:00';
            $uInfo->is_delete = 0;
        } else {
            $uInfo->avatar = $userInfo->avatar ?: $uInfo->avatar;
        }
        if (!$uInfo->save()) {
            $t->rollBack();
            return $this->getErrorResponse($uInfo);
        }

        // 用户角色表
        $userIdentity = UserIdentity::findOne([
            'user_id' => $user->id,
            'is_delete' => 0
        ]);
        if (!$userIdentity) {
            $userIdentity = new UserIdentity();
            $userIdentity->user_id = $user->id;
        }
        if (!$userIdentity->save()) {
            $t->rollBack();
            return $this->getErrorMsg($userIdentity);
        }
        // 用户平台信息表
        $userPlatform = UserPlatform::findOne([
            'user_id' => $user->id,
            'platform' => $userInfo->user_platform,
        ]);
        if (!$userPlatform) {
            $userPlatform = new UserPlatform();
            $userPlatform->mall_id = $user->mall_id;
            $userPlatform->user_id = $user->id;
            $userPlatform->platform = $userInfo->user_platform;
        }
        $userPlatform->platform_id = $userInfo->user_platform_user_id;
        $userPlatform->unionid = $userInfo->unionId;
        $userPlatform->password = $userInfo->password;
        $userPlatform->subscribe = $userInfo->subscribe;
        if (!$userPlatform->save()) {
            $t->rollBack();
            return $this->getErrorMsg($userPlatform);
        }
        $t->commit();
        $this->triggerEvent($user, $register);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'access_token' => $user->access_token,
                'update_info' => strpos($user->nickname, "微信用户") === false ? 1 : 0
            ],
        ];
    }

    private function triggerEvent($user, $register = false)
    {
        $event = new UserEvent();
        $event->sender = $this;
        $event->user = $user;
        if ($register) {
            \Yii::$app->trigger(User::EVENT_REGISTER, $event);
        }
        \Yii::$app->trigger(User::EVENT_LOGIN, $event);
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
                'username' => $userInfo->username,
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
}
