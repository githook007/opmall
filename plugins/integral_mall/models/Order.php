<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\models;


class Order extends \app\models\Order
{
    public function getIntegralOrder()
    {
        return $this->hasOne(IntegralMallOrders::className(), ['order_id' => 'id']);
    }
}
