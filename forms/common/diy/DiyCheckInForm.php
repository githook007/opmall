<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\models\Model;
use app\plugins\check_in\models\CheckInAwardConfig;
use app\plugins\check_in\models\CheckInUser;

class DiyCheckInForm extends Model
{
    public function getCheckIn()
    {
        /** @var CheckInUser $checkInUser */
        $checkInUser = CheckInUser::find()->where([
            'user_id' => \Yii::$app->user->id,
            'mall_id' => \Yii::$app->mall->id,
        ])->one();

        /** @var CheckInAwardConfig $checkInAwardConfig */
        $checkInAwardConfig = CheckInAwardConfig::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'status' => 1,
            'is_delete' => 0
        ])->one();

        return [
            'continue' => $checkInUser ? $checkInUser->continue : 0,
            'number' => $checkInAwardConfig ? $checkInAwardConfig->number : 0,
            'type' => $checkInAwardConfig ? $checkInAwardConfig->type : 'integral',
        ];
    }
}
