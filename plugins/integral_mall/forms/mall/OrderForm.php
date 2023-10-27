<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\mall;


use app\forms\mall\order\BaseOrderForm;
use app\plugins\integral_mall\models\IntegralMallOrders;

class OrderForm extends BaseOrderForm
{
    protected function getExtra($order)
    {
        $order = IntegralMallOrders::findOne(['order_id' => $order['id']]);
        return [
            'integral_num' => $order->integral_num
        ];
    }
}
