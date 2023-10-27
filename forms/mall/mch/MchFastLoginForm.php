<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\mch;

use app\bootstrap\response\ApiCode;
use app\models\Mall;
use app\models\Model;
use app\models\User;
use app\plugins\mch\models\Mch;

class MchFastLoginForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function login()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        
        try {
            $permission = \Yii::$app->role->permission;
            if(\Yii::$app->role->getName() != 'operator' && !in_array('mch', $permission)){
                throw new \Exception('无权限');
            }

            $mch = Mch::findOne([
                'id' => $this->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ]);
            if (!$mch) {
                throw new \Exception('商户不存在');
            }
            if (!$mch->mchUser) {
                throw new \Exception('商户账号不存在');
            }

            $mch->mchUser->setLoginData(User::LOGIN_MCH);
            \Yii::$app->session->set('__fast_login_operator_id', \Yii::$app->user->id);
            \Yii::$app->session->set('__fast_login_user_id', $mch->mchUser->id);
            \Yii::$app->user->login($mch->mchUser, 86400);

            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['mall/index/index']))->send();
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function backLogin()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $url = \Yii::$app->urlManager->createUrl(['plugin/mch/mall/mch/index']);
            if(empty($userId = \Yii::$app->session->get('__fast_login_operator_id'))){
                \Yii::$app->response->redirect($url)->send();
            }
            $user = User::findOne($userId);
            if(!$user){
                \Yii::$app->response->redirect($url)->send();
            }
            $mall = Mall::findOne($user->mall_id);
            if (!$mall) {
                \Yii::$app->response->redirect($url)->send();
            }
            if (($user->identity->is_admin === 1 || $user->identity->is_super_admin === 1) && !$user->adminInfo) {
                \Yii::$app->response->redirect($url)->send();
            }

            if ($user->identity->is_admin === 1 || $user->identity->is_super_admin === 1) {
                // 管理员
                $user->setLoginData(User::LOGIN_ADMIN);
            } else {
                // 员工
                $user->setLoginData(User::LOGIN_STAFF);
            }
            \Yii::$app->user->login($user, 86400);
            \Yii::$app->session->remove('__fast_login_operator_id');
            \Yii::$app->session->remove('__fast_login_user_id');

            \Yii::$app->response->redirect($url)->send();
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
