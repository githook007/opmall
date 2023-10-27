<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/14
 * Time: 16:20
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\handlers;


use app\events\OrderEvent;
use app\forms\common\order\CommonOrder;
use app\models\Order;

class OrderSalesHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_SALES, function ($event) {
            /** @var OrderEvent $event */
            \Yii::$app->setMchId($event->order->mch_id);
            $commonOrder = CommonOrder::getCommonOrder($event->order->sign);
            $orderHandler = $commonOrder->getOrderHandler();
            $handler = $orderHandler->orderSalesHandlerClass;
            $handler->orderConfig = $commonOrder->getOrderConfig();
            $handler->event = $event;
            $handler->setMall()->handle();
        });
    }
}
