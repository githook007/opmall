<?php

namespace app\plugins\erp\handlers;

use app\events\OrderEvent;
use app\models\Order;
use app\handlers\HandlerBase;
use app\plugins\erp\forms\common\data\OrderForm;

class OrderPayedHandler extends HandlerBase
{
    /**
     * 事件处理注册
     */
    public function register()
    {
        \Yii::$app->on(Order::EVENT_PAYED, function ($event) {
            \Yii::warning("---erp推送订单开始了---");
            /** @var OrderEvent $event */
            //权限判断
            $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
            if (!in_array('erp', $permission)) {
                \Yii::warning("无权限");
                return;
            }
            \Yii::$app->setMchId($event->order->mch_id);
            (new OrderForm(['orderList' => [$event->order]]))->upload();
        });
    }
}
