<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\teller\forms\web;

use app\bootstrap\response\ApiCode;
use app\forms\api\mall_member\VerifyPayPasswordForm;
use app\models\User;

class TellerVerifyPayPasswordForm extends VerifyPayPasswordForm
{
    public $user_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'user_id' => '会员ID'
        ]);
    }

    public function getUser()
    {
        $user = User::find()->andWhere([
            'mall_id' => \Yii::$app->mall->id,
            'id' => $this->user_id,
            'is_delete' => 0
        ])
            ->with('userInfo')
            ->one();

        return $user;
    }
}
