<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/14
 * Time: 16:10
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;

use app\events\OrderEvent;
use app\models\Mall;
use app\models\Order;
use app\models\OrderRefund;
use yii\queue\JobInterface;

class OrderSalesJob extends BaseJob implements JobInterface
{
    public $orderId;

    public function execute($queue)
    {
        \Yii::error('order sales job->>' . $this->orderId);
        $this->setRequest();
        $order = Order::findOne([
            'id' => $this->orderId,
            'is_delete' => 0,
            'is_send' => 1,
            'is_confirm' => 1,
            'is_sale' => 0,
        ]);
        if (!$order) {
            \Yii::warning('创建自动过售后，订单不存在');
            return false;
        }
        $mall = Mall::findOne(['id' => $order->mall_id]);
        \Yii::$app->setMall($mall);

        $orderRefundList = OrderRefund::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();
        if ($orderRefundList) {
            /* @var OrderRefund[] $orderRefundList */
            foreach ($orderRefundList as $orderRefund) {
                if ($orderRefund->status != 3 && in_array($orderRefund->type, [1, 3]) && $orderRefund->is_refund == 0) {
                    return false;
                } else if ($orderRefund->status != 3 && $orderRefund->type == 2 && $orderRefund->is_confirm == 0) {
                    return false;
                }
            }
        }

        $order->is_sale = 1;
        if ($order->save()) {
            $event = new OrderEvent([
                'order' => $order,
            ]);
            \Yii::$app->trigger(Order::EVENT_SALES, $event);
        }
    }
}
