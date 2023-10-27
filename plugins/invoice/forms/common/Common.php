<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */

namespace app\plugins\invoice\forms\common;

use app\models\Model;
use app\plugins\invoice\models\InvoiceSetting;

class Common extends Model
{
    public static $setting;

    public static function getSetting()
    {
        if (self::$setting) {
            return self::$setting;
        }
        $setting = InvoiceSetting::findOne(['mall_id' => \Yii::$app->mall->id]);
        //防事务
        self::$setting = $setting;
        return $setting;
    }
}
