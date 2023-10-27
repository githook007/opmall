<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/23 16:31
 */


namespace app\handlers;


use app\events\OrderEvent;
use app\forms\common\order\CommonOrder;
use app\models\Order;

class OrderCreatedHandler extends HandlerBase
{
    /**
     * 事件处理
     */
    public function register()
    {
        \Yii::$app->on(Order::EVENT_CREATED, function ($event) {
            /** @var OrderEvent $event */
            \Yii::$app->setMchId($event->order->mch_id);
            $commonOrder = CommonOrder::getCommonOrder($event->order->sign);
            $orderHandler = $commonOrder->getOrderHandler();
            $handler = $orderHandler->orderCreatedHandlerClass;
            $handler->orderConfig = $commonOrder->getOrderConfig();
            $handler->event = $event;
            $handler->setMall()->handle();
        });
    }
}
