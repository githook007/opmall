<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/25
 * Time: 4:44 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\api\cart\CartForm;
use app\models\User;
use app\plugins\scrm\exception\ScrmException;

class CartListForm extends CartForm
{
    public $user_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
        ]);
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $user = User::findOne([
                'id' => $this->user_id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id
            ]);
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }
            \Yii::$app->user->login($user);
            return parent::search();
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
