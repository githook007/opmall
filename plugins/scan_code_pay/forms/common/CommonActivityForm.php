<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scan_code_pay\forms\common;


use app\models\Model;
use app\plugins\scan_code_pay\models\ScanCodePayActivities;

class CommonActivityForm extends Model
{
    public function search()
    {
        $activity = ScanCodePayActivities::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'status' => 1,
            'is_delete' => 0
        ])->andWhere([
            'and',
            ['<', 'start_time', mysql_timestamp()],
            ['>', 'end_time', mysql_timestamp()]
        ])
            ->with(['groups.members', 'groups.rules', 'groups.rules.scanCards.cards', 'groups.rules.scanCoupons.coupons'])
            ->one();

        return $activity;
    }
}