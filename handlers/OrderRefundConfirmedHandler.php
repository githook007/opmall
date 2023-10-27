<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\handlers;

use app\events\OrderRefundEvent;
use app\forms\common\share\AddShareOrder;
use app\forms\wlhulian\CommonForm;
use app\jobs\OrderSalesJob;
use app\models\CoreQueueData;
use app\models\CouponMallRelation;
use app\models\OrderRefund;
use app\models\UserCard;

class OrderRefundConfirmedHandler extends HandlerBase
{
    /**
     * 事件处理注册
     */
    public function register()
    {
        \Yii::$app->on(OrderRefund::EVENT_REFUND, function ($event) {
            /** @var OrderRefundEvent $event */
            \Yii::$app->setMchId($event->order_refund->mch_id);
            $orderDetail = $event->order_refund->detail;
            $orderDetail->refund_status = 2;
            // 商家同意退款 销毁订单商品赠送的卡券
            if (in_array($event->order_refund->type, [1,3]) && $event->order_refund->status == 2) {
                $orderDetail->is_refund = 1;

                /* @var UserCard[] $userCards */
                $userCards = UserCard::find()->where([
                    'order_id' => $event->order_refund->order_id,
                    'order_detail_id' => $event->order_refund->order_detail_id
                ])->all();
                foreach ($userCards as $userCard) {
                    $userCard->is_delete = 1;
                    $userCard->card->updateCount('add', 1);
                    $res = $userCard->save();
                    if (!$res) {
                        \Yii::error('卡券销毁事件处理异常');
                    }
                }

                /** @var CouponMallRelation[] $couponsRel */
                $couponsRel = CouponMallRelation::find()->with('userCoupon')->where(['order_id' => $orderDetail->order_id, 'is_delete' => 0])->all();
                foreach ($couponsRel as $coupon) {
                    if(!$coupon->userCoupon || $coupon->userCoupon->is_use){
                        continue;
                    }
                    $coupon->userCoupon->is_delete = 1;
                    $coupon->userCoupon->coupon->updateCount(1, 'add');
                    $res = $coupon->userCoupon->save();
                    if (!$res) {
                        \Yii::error('优惠券销毁事件处理异常：'.var_export($coupon->userCoupon->getFirstErrors()));
                    }
                    $coupon->is_delete = 1;
                    $coupon->save();
                }

                $price = $orderDetail->total_price - min($orderDetail->total_price, $event->order_refund->reality_refund_price);
                (new AddShareOrder())->refund($orderDetail, $price);

                // 第三方配送取消 @czs
                if($orderDetail->order->send_type == 2) {
                    if (isset($orderDetail->order->detailExpress[0])) {
                        if($orderDetail->order->detailExpress[0]->express_type == '聚合配送'){
                            CommonForm::cancel($orderDetail->order);
                        }
                    }
                }
            }
            $orderDetail->save();

            // 判断queue队列中的售后是否已经触发
            $queueId = CoreQueueData::select($event->order_refund->order->token);
            if ($queueId && !\Yii::$app->queue->isDone($queueId)) {
                // 若未触发
                return;
            } else {
                // 若已触发，则重新添加
                $id = \Yii::$app->queue->delay(0)->push(new OrderSalesJob([
                    'orderId' => $event->order_refund->order_id
                ]));
                CoreQueueData::add($id, $event->order_refund->order->token);
            }
        });
    }
}
