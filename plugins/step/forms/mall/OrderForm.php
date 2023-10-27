<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\step\forms\mall;

use app\forms\mall\order\BaseOrderForm;
use app\plugins\step\models\StepOrder;

class OrderForm extends BaseOrderForm
{
    protected function getExtra($order)
    {
        $order = StepOrder::findOne(['order_id' => $order['id']]);
        return [
            'currency' => $order->currency ?? ''
        ];
    }
}
