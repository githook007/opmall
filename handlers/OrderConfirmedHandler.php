<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/14
 * Time: 16:06
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\handlers;

use app\events\OrderEvent;
use app\forms\common\coupon\CommonCouponGoodsSend;
use app\forms\common\order\CommonOrder;
use app\forms\common\order\weixin\OrderForm;
use app\forms\common\prints\Exceptions\PrintException;
use app\jobs\OrderSalesJob;
use app\jobs\PrintJob;
use app\models\CoreQueueData;
use app\models\Order;

class OrderConfirmedHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_CONFIRMED, function ($event) {
            /** @var OrderEvent $event */
            \Yii::$app->setMchId($event->order->mch_id);

            OrderForm::getCommon(['order' => $event->order])->receive();

            $orderAutoSaleTime = \Yii::$app->mall->getMallSettingOne('after_sale_time');
            if (is_numeric($orderAutoSaleTime) && $orderAutoSaleTime >= 0) {
                // 订单过售后
                $id = \Yii::$app->queue->delay($orderAutoSaleTime * 86400)->push(new OrderSalesJob([
                    'orderId' => $event->order->id
                ]));
                CoreQueueData::add($id, $event->order->token);
                $autoSalesTime = strtotime($event->order->confirm_time) + $orderAutoSaleTime * 86400;
                $event->order->auto_sales_time = mysql_timestamp($autoSalesTime);
                $event->order->save();
            }
            $commonOrder = CommonOrder::getCommonOrder($event->order->sign);
            $orderConfig = $commonOrder->getOrderConfig();
            try {
                if ($orderConfig->is_print != 1) {
                    throw new PrintException($event->order->sign . '未开启小票打印');
                }
                $job = new PrintJob();
                $job->mall = \Yii::$app->mall;
                $job->order = $event->order;
                $job->orderType = 'confirm';
                \Yii::$app->queue->delay(0)->push($job);
            } catch (PrintException $e) {
                \Yii::error("小票打印机打印出错：" . $e->getMessage());
            }

            if(\Yii::$app->mall->getMallSettingOne("goods_coupon_usage_scenario") == 2){
                \Yii::warning('购买商品并收货后赠送优惠券发放数据');
                try {
                    $couponSendForm = new CommonCouponGoodsSend();
                    $couponSendForm->user = $event->order->user;
                    $couponSendForm->mall = \Yii::$app->mall;
                    $couponSendForm->order_id = $event->order->id;
                    $couponSendForm->send();
                } catch (\Exception $exception) {
                    \Yii::error('赠送优惠券发放失败: ' . $exception->getMessage());
                }
            }
        });
    }
}
