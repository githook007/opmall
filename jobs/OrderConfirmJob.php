<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/14
 * Time: 15:56
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;

use app\forms\common\order\CommonOrder;
use app\models\Mall;
use app\models\Order;
use yii\queue\JobInterface;

class OrderConfirmJob extends BaseJob implements JobInterface
{
    public $orderId;

    public function execute($queue)
    {
        \Yii::error('order confirm job ->>' . $this->orderId);
        $this->setRequest();
        $order = Order::findOne([
            'id' => $this->orderId,
            'is_delete' => 0,
            'is_send' => 1,
            'is_confirm' => 0
        ]);
        if (!$order) {
            return true;
        }
        $mall = Mall::findOne(['id' => $order->mall_id]);
        \Yii::$app->setMall($mall);
        if ($order->pay_type == 2) {
            \Yii::error('货到付款的无法自动收货');
            return true;
        }
        CommonOrder::getCommonOrder($order->sign)->confirm($order);

        return true;
    }
}
