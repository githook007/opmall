<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/20
 * Time: 2:39 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\erp\handlers;

use app\events\OrderEvent;
use app\handlers\HandlerBase;
use app\models\Order;
use app\plugins\erp\forms\common\data\OrderForm;

class OrderSentHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_SENT, function ($event) {
            try {
                \Yii::warning('---erp 订单发货事件---');
                $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
                if (!in_array('erp', $permission)) {
                    \Yii::warning("无权限");
                    return;
                }
                /* @var OrderEvent $event */
                \Yii::$app->setMchId($event->order->mch_id);
                $form = new OrderForm();
                $form->order = $event->order;
                $form->sent();
            } catch (\Exception $exception) {
                \Yii::warning($exception);
            }
        });
    }
}
