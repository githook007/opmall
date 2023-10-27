<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/28
 * Time: 11:23 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\models\User;
use app\plugins\scrm\exception\ScrmException;

class FootprintForm extends \app\forms\api\FootprintForm
{
    public $user_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
        ]);
    }

    public function footprint()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            /** @var User $user */
            $user = User::find()->where([
                'id' => $this->user_id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id
            ])->with('userInfo', 'userPlatform')->one();
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }
            \Yii::$app->user->login($user);
            $this->start_time = '0';
            return parent::footprint();
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
