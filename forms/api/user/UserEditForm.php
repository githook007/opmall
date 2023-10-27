<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\user;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\User;

class UserEditForm extends Model
{
    public $nickname;
    public $avatar;

    public function rules()
    {
        return [
            [['nickname'], 'string', 'max' => 100],
            [['avatar'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '昵称',
            'avatar' => '头像',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };

        /* @var User $form */
        $form = User::find()->alias('u')
            ->where(['u.id' => \Yii::$app->user->id])
            ->one();

        if (!$form) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户不存在'
            ];
        }

        $t = \Yii::$app->db->beginTransaction();
        try {
            $form->userInfo->avatar = $this->avatar;
            $form->nickname = $this->nickname;

            if (!$form->userInfo->save()) {
                throw new \Exception($this->getErrorMsg($form->userInfo));
            }

            if (!$form->save()) {
                throw new \Exception($this->getErrorMsg($form));
            }

            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
