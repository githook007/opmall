<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\common;


use app\models\Mall;
use app\models\Model;
use app\plugins\mch\models\MchMallSetting;

/**
 * @property Mall $mall
 */
class MallSettingForm extends Model
{
    public function search($mchId)
    {
        $setting = MchMallSetting::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'mch_id' => $mchId
        ])->asArray()->one();

        if (!$setting) {
            $setting = $this->getDefault();
        }

        $setting['is_share'] = (int)$setting['is_share'];
        $setting['is_coupon'] = (int)$setting['is_coupon']; // @czs

        return $setting;
    }

    private function getDefault()
    {
        return [
            'is_share' => 0,
            'is_coupon' => 0, // @czs
        ];
    }
}
