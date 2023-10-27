<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 2:57 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\common;

use app\bootstrap\payment\PaymentNotify;
use app\forms\api\order\OrderRefundSubmitForm;
use app\helpers\ArrayHelper;
use app\models\Mall;
use app\models\Order;
use app\models\OrderRefund;
use app\models\PaymentOrderUnion;
use app\models\RefundAddress;
use app\plugins\bonus\events\OrderRefundEvent;
use app\plugins\minishop\forms\CheckForm;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopOrder;
use app\plugins\minishop\models\MinishopRefund;
use yii\helpers\Json;

// @czs 事件回执处理订单数据
class CommonNotify extends Model
{
    public static $instance;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new self();
        return self::$instance;
    }

    /**
     * @param $orderInfo
     * @param $third
     * @return mixed
     * @throws \Exception
     * 订单支付回调
     */
    public function handleOrderPay($orderInfo, $third = false)
    {
        $t = \Yii::$app->db->transaction;
        try {
            if($third){
                $where = [
                    'order_id' => $orderInfo['order_id'],
                    'payment_order_union_no' => $orderInfo['out_order_id'],
                ];
            }else{
                $where = [
                    'order_id' => $orderInfo['order_id'],
                    "mall_id" => \Yii::$app->mall->id,
                ];
            }
            $miniShopOrderList = MinishopOrder::find()->where($where)->all();

            if (!count($miniShopOrderList)) {
                throw new \Exception('交易组件支付订单不存在id:'.$orderInfo['order_id']);
            }
            /** @var MinishopOrder $miniShopOrder */
            foreach ($miniShopOrderList as $miniShopOrder) {
                if($miniShopOrder->status != 10){
                    continue;
                }
                $miniShopOrder->status = 20;
                if (!$miniShopOrder->save()) {
                    throw new \Exception($this->getErrorMsg($miniShopOrder));
                }
            }

            $paymentOrderUnion = PaymentOrderUnion::findOne($miniShopOrder->payment_order_union_id);
            if(!$paymentOrderUnion){
                throw new \Exception('支付订单不存在id:'.$miniShopOrder->payment_order_union_id);
            }
            if($third){
                \Yii::$app->setMall(Mall::findOne($paymentOrderUnion->mall_id));
            }
            if ($paymentOrderUnion->app_version) {
                \Yii::$app->setAppVersion($paymentOrderUnion->app_version);
            }
            if ($paymentOrderUnion->is_pay === 0) {
                $paymentOrders = $paymentOrderUnion->paymentOrder;
                $paymentOrderUnion->is_pay = 1;
                $paymentOrderUnion->pay_type = 13;
                if (!$paymentOrderUnion->save()) {
                    throw new \Exception($paymentOrderUnion->getFirstErrors());
                }
                foreach ($paymentOrders as $paymentOrder) {
                    $Class = $paymentOrder->notify_class;
                    if (!class_exists($Class)) {
                        continue;
                    }
                    $paymentOrder->is_pay = 1;
                    $paymentOrder->pay_type = 1;
                    if (!$paymentOrder->save()) {
                        throw new \Exception($paymentOrder->getFirstErrors());
                    }
                    /** @var PaymentNotify $notify */
                    $notify = new $Class();
                    $po = new \app\bootstrap\payment\PaymentOrder([
                        'orderNo' => $paymentOrder->order_no,
                        'amount' => (float)$paymentOrder->amount,
                        'title' => $paymentOrder->title,
                        'notifyClass' => $paymentOrder->notify_class,
                        'payType' => \app\bootstrap\payment\PaymentOrder::PAY_TYPE_WECHAT
                    ]);
                    $notify->notify($po);
                }
            }
            $t->commit();
        }catch (\Exception $e){
            $t->rollBack();
            \Yii::error(sprintf('交易组件支付回调处理失败，line：%s，msg：%s', $e->getLine(), $e->getMessage()));
        }
    }

    /**
     * @param $aftersaleInfo
     * @param $third
     * @return mixed
     * @throws \Exception
     * 创建售后 czs
     */
    public function handleCreateAftersale($aftersaleInfo, $third = false)
    {
        if($third){
            $where = [
                'order_id' => $aftersaleInfo['order_id'],
                'payment_order_union_no' => $aftersaleInfo['out_order_id']
            ];
        }else{
            $where = [
                'order_id' => $aftersaleInfo['order_id'],
                "mall_id" => \Yii::$app->mall->id,
            ];
        }
        $miniShopOrderList = MinishopOrder::find()->where($where)->all();

        if (!count($miniShopOrderList)) {
            \Yii::warning('交易组件支付订单不存在id:'.$aftersaleInfo['order_id']);
            return true;
        }
        if($third){
            \Yii::$app->setMall(Mall::findOne($miniShopOrderList[0]->mall_id));
        }
        /** @var MinishopOrder $miniShopOrder */
        foreach ($miniShopOrderList as $k => $miniShopOrder) {
            $minishopRefund = MinishopRefund::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'aftersale_id' => $aftersaleInfo['aftersale_id'],
                'order_id' => $miniShopOrder->platform_order_id
            ]);
            if ($minishopRefund) {
                \Yii::warning('交易组件售后单已存在');
                unset($miniShopOrderList[$k]);
            }
        }
        $miniShopOrderList = array_values($miniShopOrderList);
        if(!$miniShopOrderList){
            return true;
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            $form = new CheckForm();
            $plugin = $form->check();
            $shopService = $plugin->getShopService();
            $res = $shopService->sale->get(['aftersale_id' => $aftersaleInfo['aftersale_id']]);
            $afterSalesOrder = $res['after_sales_order'] ?? null;
            if (!$afterSalesOrder) {
                throw new \Exception('查询交易组件售后单详情失败' . var_export($res, true));
            }
            if ($afterSalesOrder['type'] == 2) {
                $type = 1;
            } elseif ($afterSalesOrder['type'] == 1) {
                $type = 3;
            } else {
                $type = 2;
            }
            foreach ($miniShopOrderList as $miniShopOrder) {
                $order = Order::findOne($miniShopOrder->platform_order_id);
                if (!$order) {
                    \Yii::warning('订单不存在id:' . $miniShopOrder->platform_order_id);
                    continue;
                }
                if ($order->is_pay != 1) {
                    \Yii::warning('订单未支付，id:' . $miniShopOrder->platform_order_id);
                    continue;
                }
                if ($order->cancel_status != 0) {
                    \Yii::warning("订单不支持生成售后，cancel_status：{$order->cancel_status}，id:" . $miniShopOrder->platform_order_id);
                    continue;
                }
                $orderDetail = ArrayHelper::index($order->detail, "goods_id");
                $orderDetailId = $orderDetail[$afterSalesOrder['product_info']['out_product_id']]['id'] ?? null;
                if (!$orderDetailId) {
                    \Yii::warning("订单商品详情找不到");
                    continue;
                }

                \Yii::$app->user->setIdentity($order->user);
                $form = new OrderRefundSubmitForm();
                $data = Json::decode($miniShopOrder->data, true);
                foreach ($data['order_detail']['product_infos'] as $detail) {
                    if ($afterSalesOrder['product_info']['out_product_id'] == $detail['out_product_id']) {
                        $pic_list = @array_column($afterSalesOrder['media_list'], "url");
                        $form->attributes = [
                            'id' => $orderDetailId,
                            'type' => $type,
                            'pic_list' => \Yii::$app->serializer->encode($pic_list ?? []),
                            'refund_price' => $afterSalesOrder['orderamt'] / 100,
                            'remark' => $afterSalesOrder['refund_reason'],
                            'cause' => '其他',
                            'goods_status' => $type == 3 ? '未收到货' : '已收到货',
                        ];
                        $orderRefund = $form->create();
                        $form->notice($orderRefund);
                        break;
                    }
                }
                if(empty($orderRefund)){
                    \Yii::warning("未生成售后单");
                    continue;
                }
                $aftersaleInfo['time'] = mysql_timestamp();

                $minishopRefund = new MinishopRefund();
                $minishopRefund->mall_id = \Yii::$app->mall->id;
                $minishopRefund->order_id = $order->id;
                $minishopRefund->order_refund_id = $orderRefund->id;
                $minishopRefund->aftersale_id = $aftersaleInfo['aftersale_id'];
                $minishopRefund->status = 0;
                $minishopRefund->aftersale_infos = Json::encode($aftersaleInfo, JSON_UNESCAPED_UNICODE);
                if (!$minishopRefund->save()) {
                    throw new \Exception($this->getErrorMsg($minishopRefund));
                }
            }
            $t->commit();
        } catch (\Exception $e) {
            $t->rollBack();
            \Yii::error(sprintf('交易组件用户提交售后申请回调处理失败，line：%s，msg：%s', $e->getLine(), $e->getMessage()));
        }
        return true;
    }

    public function handleUpdateAftersale($aftersaleInfo, $third = false){
        if($third){
            $where = [
                'aftersale_id' => $aftersaleInfo['aftersale_id'],
            ];
        }else{
            $where = [
                'aftersale_id' => $aftersaleInfo['aftersale_id'],
                "mall_id" => \Yii::$app->mall->id,
            ];
        }
        $minishopRefund = MinishopRefund::findOne($where);
        if (!$minishopRefund) {
            \Yii::warning('交易组件售后单不存在');
            return true;
        }
        if($third){
            \Yii::$app->setMall(Mall::findOne($minishopRefund->mall_id));
        }
        $orderRefund = $minishopRefund->orderRefund;
        if(!$orderRefund){
            \Yii::warning('系统售后单不存在');
            return true;
        }

        try {
            $form = new CheckForm();
            $plugin = $form->check();
            $shopService = $plugin->getShopService();
            $res = $shopService->sale->get(['aftersale_id' => $aftersaleInfo['aftersale_id']]);
            $afterSalesOrder = $res['after_sales_order'] ?? null;
            if (!$afterSalesOrder) {
                throw new \Exception('查询交易组件售后单详情失败' . var_export($res, true));
            }
            $pic_list = @array_column($afterSalesOrder['media_list'], "url");
            $orderRefund->pic_list = \Yii::$app->serializer->encode($pic_list ?? []);
            $orderRefund->refund_price = $afterSalesOrder['orderamt'] / 100;
            $orderRefund->remark = $afterSalesOrder['refund_reason'];
            switch ($afterSalesOrder['status']){
                case 1: // 用户取消售后申请
                    return $this->handleCancelAftersale($aftersaleInfo, $third);
                case 4: // 商家拒绝退款
                case 5: // 商家拒绝退货
                    $orderRefund->status = 3;
                    $orderRefund->merchant_remark = '商家拒绝了您的售后申请';
                    $orderRefund->status_time = mysql_timestamp();
                    if (!$orderRefund->save()) {
                        throw new \Exception($this->getErrorMsg($orderRefund));
                    }
                    \Yii::$app->trigger(OrderRefund::EVENT_REFUND, new OrderRefundEvent(['order_refund' => $orderRefund]));
                case 2: // 商家处理退款申请中
                    if (!$orderRefund->save()) {
                        throw new \Exception($this->getErrorMsg($orderRefund));
                    }
                    break;
                case 6: // 待用户退货
                case 8: // 待商家收货
                    if($orderRefund->status == 1){
                        $orderRefund->status = 2;
                        $orderRefund->status_time = mysql_timestamp();
                    }
                    if ($orderRefund->type == 3) {
                        $orderRefund->is_confirm = 1;
                        $orderRefund->is_send = 1;
                    } else {
                        if(!$orderRefund->address_id && $afterSalesOrder['return_address_info'] && !empty($afterSalesOrder['return_address_info']['tel_number'])) {
                            $address = RefundAddress::findOne([
                                'mall_id' => \Yii::$app->mall->id,
                                'mch_id' => $orderRefund->order->mch_id,
                                'mobile' => $afterSalesOrder['return_address_info']['tel_number'],
                                'name' => $afterSalesOrder['return_address_info']['receiver_name'],
                                'is_delete' => 0,
                            ]);
                            if ($address) {
                                $orderRefund->address_id = $address->id;
                            }
                        }
                    }
                    if(!empty($afterSalesOrder['return_info']['waybill_id'])){
                        $orderRefund->is_send = 1;
                        $orderRefund->send_time = mysql_timestamp(substr($afterSalesOrder['return_info']['order_return_time'], 0, 10));
                        $orderRefund->express = $afterSalesOrder['return_info']['delivery_name'];
                        $orderRefund->express_no = $afterSalesOrder['return_info']['waybill_id'];
                    }
                    if (!$orderRefund->save()) {
                        throw new \Exception($this->getErrorMsg($orderRefund));
                    }
                    break;
                case 13: // 退款成功
                    \Yii::error('退款成功');
                    \Yii::error($aftersaleInfo);
                    break;
                case 25: // 平台退款失败
                    \Yii::error('平台退款失败');
                    \Yii::error($aftersaleInfo);
                    break;
            }
        }catch (\Exception $e){
            \Yii::error(sprintf('交易组件更新售后申请回调处理失败，line：%s，msg：%s', $e->getLine(), $e->getMessage()));
        }
        return true;
    }

    public function handleCancelAftersale($aftersaleInfo, $third = false){
        if($third){
            $where = [
                'aftersale_id' => $aftersaleInfo['aftersale_id'],
            ];
        }else{
            $where = [
                'aftersale_id' => $aftersaleInfo['aftersale_id'],
                "mall_id" => \Yii::$app->mall->id,
            ];
        }
        $minishopRefund = MinishopRefund::findOne($where);
        if (!$minishopRefund) {
            \Yii::warning('交易组件售后单不存在');
            return true;
        }
        if(!$minishopRefund->orderRefund){
            \Yii::warning('系统售后单不存在');
            return true;
        }
        $orderRefund = $minishopRefund->orderRefund;
        if($orderRefund->is_delete == 1){
            \Yii::warning('系统售后单已取消了');
            return true;
        }
        if($orderRefund->status != 1){
            \Yii::warning('系统售后单已不支持取消了');
            return true;
        }

        $t = \Yii::$app->db->beginTransaction();
        try {
            $orderRefund->is_delete = 1;
            if (!$orderRefund->save()) {
                throw new \Exception($this->getErrorMsg($orderRefund));
            }
            $minishopRefund->status = 1;
            if (!$minishopRefund->save()) {
                throw new \Exception($this->getErrorMsg($minishopRefund));
            }
            $t->commit();
        }catch (\Exception $e){
            $t->rollBack();
            \Yii::error(sprintf('交易组件取消售后申请回调处理失败，line：%s，msg：%s', $e->getLine(), $e->getMessage()));
        }
        return true;
    }
}
