<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\common;


use app\events\OrderEvent;
use app\models\Order;

class CommonOrder
{

    /**
     * @param Order $order
     * 礼品卡订单自动完成
     */
    public function autoSend(Order $order)
    {
        $order->is_send = 1;
        $order->send_time = mysql_timestamp();
        $order->is_confirm = 1;
        $order->confirm_time = mysql_timestamp();
        $order->is_sale = 1;
        if ($order->save()) {
            \Yii::$app->trigger(Order::EVENT_CONFIRMED, new OrderEvent([
                'order' => $order,
            ]));
            \Yii::$app->trigger(Order::EVENT_SALES, new OrderEvent([
                'order' => $order,
            ]));
        }
    }
}