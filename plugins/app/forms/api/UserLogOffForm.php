<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/17
 * Time: 9:27 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\api;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\UserInfo;
use app\models\UserPlatform;

class UserLogOffForm extends Model
{
    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function handle(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $userPlatform = UserPlatform::findOne([
            'user_id' => \Yii::$app->user->id,
            "platform" => UserInfo::PLATFORM_APP,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if(!$userPlatform){
            return [
                "code" => ApiCode::CODE_ERROR,
                'msg' => '账号已删除'
            ];
        }
        $tran = \Yii::$app->db->beginTransaction();
        try {
            $user = $userPlatform->user;
            if ($user) {
                $user->is_delete = 1;
                if(!$user->save()){
                    throw new \Exception($this->getErrorMsg($user));
                }
                if($user->userInfo) {
                    $user->userInfo->is_delete = 1;
                    if (!$user->userInfo->save()) {
                        throw new \Exception($this->getErrorMsg($user->userInfo));
                    }
                }
                if($user->identity) {
                    $user->identity->is_delete = 1;
                    if (!$user->identity->save()) {
                        throw new \Exception($this->getErrorMsg($user->identity));
                    }
                }
                if($user->share) {
                    $user->share->is_delete = 1;
                    if (!$user->share->save()) {
                        throw new \Exception($this->getErrorMsg($user->share));
                    }
                }
            }
            $userPlatform->mall_id = -$userPlatform->mall_id;
            if(!$userPlatform->save()){
                throw new \Exception($this->getErrorMsg($userPlatform));
            }
            $tran->commit();
        }catch (\Exception $e){
            $tran->rollBack();
            return [
                "code" => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
        return [
            "code" => ApiCode::CODE_SUCCESS,
            'msg' => '注销成功'
        ];
    }
}
