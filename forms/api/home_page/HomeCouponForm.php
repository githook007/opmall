<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\home_page;


use app\forms\common\coupon\CommonCouponList;
use app\models\Model;

class HomeCouponForm extends Model
{
    public function getCouponList()
    {
        $common = new CommonCouponList();
        $common->user = \Yii::$app->user->identity;
        $list = $common->getList();

        return $common->getIndexData($list);
    }
}
