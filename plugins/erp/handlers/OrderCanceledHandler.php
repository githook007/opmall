<?php

namespace app\plugins\erp\handlers;

use app\plugins\bonus\events\OrderEvent;
use app\models\Order;
use app\handlers\HandlerBase;
use app\plugins\erp\forms\common\data\OrderForm;

class OrderCanceledHandler extends HandlerBase
{

    /**
     * 事件处理注册
     */
    public function register()
    {
        \Yii::$app->on(Order::EVENT_CANCELED, function ($event) {
            \Yii::warning('---erp订单取消事件---');
            try {
                $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
                if (!in_array('erp', $permission)) {
                    \Yii::warning("无权限");
                    return;
                }
                /** @var OrderEvent $event */
                \Yii::$app->setMchId($event->order->mch_id);
                (new OrderForm(['orderList' => [$event->order]]))->cancel();
            } catch (\Exception $exception) {
                \Yii::error($exception);
            }
        });
    }
}
