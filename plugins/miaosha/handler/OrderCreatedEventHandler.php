<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\handler;

use app\handlers\orderHandler\OrderCreatedHandlerClass;
use app\jobs\OrderCancelJob;
use app\plugins\miaosha\forms\common\SettingForm;

class OrderCreatedEventHandler extends OrderCreatedHandlerClass
{
    public function setAutoCancel()
    {
        $setting = (new SettingForm())->search();
        if (is_numeric($setting['over_time']) && $setting['over_time'] > 0) {
            // 订单自动取消任务
            \Yii::$app->queue->delay($setting['over_time'] * 60)->push(new OrderCancelJob([
                'orderId' => $this->event->order->id,
            ]));
            $autoCancelTime = strtotime($this->event->order->created_at) + $setting['over_time'] * 60;
            $this->event->order->auto_cancel_time = mysql_timestamp($autoCancelTime);
            $this->event->order->save();
        }
        return $this;
    }
}
