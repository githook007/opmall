<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\mch;


use app\models\Model;
use app\plugins\mch\models\MchMallSetting;

class MchMallSettingForm extends Model
{
    public function search()
    {
        $mchMallSetting = MchMallSetting::findOne(['mch_id' => \Yii::$app->user->identity->mch_id]);
        return $mchMallSetting;
    }
}
