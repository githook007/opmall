<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\order;

use app\bootstrap\Plugin;
use app\bootstrap\response\ApiCode;
use app\forms\api\goods\MallGoods;
use app\forms\common\CommonDelivery;
use app\forms\common\order\CommonOrderDetail;
use app\forms\common\template\TemplateList;
use app\models\Model;
use app\models\Order;
use app\models\orderDetail;
use app\models\OrderRefund;
use app\models\PaymentOrderUnion;
use app\plugins\mch\models\Mch;
use app\plugins\mch\models\MchSetting;

class OrderEditForm extends Model
{
    public $id; // 订单ID
    public $action_type; //操作订单的类型,1 订单核销详情|

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['action_type'], 'string'],
        ];
    }

    public function getDetail()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }

            $form = new CommonOrderDetail();
            $form->id = $this->id;
            $form->is_detail = 1;
            $form->is_goods = 1;
            $form->is_refund = 1;
            $form->is_array = 1;
            $form->is_store = 1;
            $form->relations = ['detailExpress.expressRelation.orderDetail', 'detailExpressRelation'];
            $form->is_vip_card = 1;
            $order = $form->search();

            if (!$order) {
                throw new \Exception('订单不存在');
            }

            $goodsNum = 0;
            $memberDeductionPriceCount = 0;
            // 统一商品信息，用于前端展示
            $orderRefund = new OrderRefund();
            $order['is_show_send_type'] = 1;
            $order['is_can_apply_sales'] = 1;
            $order['is_show_express'] = 0;
            $priceList = [];
            foreach ($order['detail'] as &$item) {
                $goodsNum += $item['num'];
                $memberDeductionPriceCount += $item['member_discount_price'];
                $goodsInfo = MallGoods::getGoodsData($item);
                $item['is_show_apply_refund'] = 0;

                if ($item['refund']) {
                    // 售后订单 状态
                    $item['refund']['status_text'] = $orderRefund->statusText($item['refund']);
                    $refundList = OrderRefund::find()->andWhere(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'order_detail_id' => $item['id']])->all();
                    // 售后被拒绝后可再申请一次
                    if ($item['refund']['status'] == 3 && count($refundList) == 1) {
                        $item['is_show_apply_refund'] = 1;
                    }
                }else{
                    if($order['is_sale'] == 0){
                        $item['is_show_apply_refund'] = 1;
                    }
                }

                $form_data = empty($item['form_data']) ? "[]" : $item['form_data'];
                $item['form_data'] = \Yii::$app->serializer->decode($form_data);
                $item['goods_info'] = $goodsInfo;
                $order['is_show_send_type'] = $goodsInfo['is_show_send_type'];
                $order['is_can_apply_sales'] = $goodsInfo['is_can_apply_sales']; // 是否显示售后按钮
                $order['is_show_express'] = $order['is_show_express'] || $goodsInfo['is_show_express'] ? 1 : 0; // 展示运费（只要有一个商品支持展示运费就需要展示）
                $order['goods_type'] = $goodsInfo['goods_type']; // 商品类型

                $priceList[] = [
                    'label' => '小计',
                    'value' => $item['total_price'],
                ];
            }

            $merchantRemarkList = [];
            foreach ($order['detailExpress'] as &$detailExpress) {
                if ($detailExpress['send_type'] == 1 && $detailExpress['merchant_remark']) {
                    $merchantRemarkList[] = $detailExpress['merchant_remark'];
                }

                $goodsNum = 0;
                foreach ($detailExpress['expressRelation'] as &$expressRelation) {
                    $goodsNum += $expressRelation['orderDetail']['num'];
                    $expressRelation['orderDetail']['goods_info'] = \Yii::$app->serializer->decode($expressRelation['orderDetail']['goods_info']);
                }
                $detailExpress['goods_num'] = $goodsNum;
                unset($expressRelation);
            }
            unset($detailExpress);
            $order['merchant_remark_list'] = $merchantRemarkList;

            // 订单状态
            $order['status_text'] = (new Order())->orderStatusText($order);
            $order['pay_type_text'] = (new Order())->getPayTypeText($order['pay_type']);
            // 订单商品总数
            $order['goods_num'] = $goodsNum;
            $order['member_deduction_price_count'] = price_format($memberDeductionPriceCount);
            if ($order['send_type'] == 2) {
                $order['delivery_config'] = CommonDelivery::getInstance()->getConfig();
            }

            $order['plugin_data'] = (new Order())->getPluginData($order, $priceList);
            try {
                if ($order['sign']) {
                    $PluginClass = 'app\\plugins\\' . $order['sign'] . '\\Plugin';
                    /** @var Plugin $pluginObject */
                    $object = new $PluginClass();
                    if (method_exists($object, 'changeOrderInfo')) {
                        $order = $object->changeOrderInfo($order);
                    }
                }
            } catch (\Exception $exception) {
            }

            // 商品类型
            $typeData = [];
            $typePlugin = \Yii::$app->plugin->getAllTypePlugins();
            foreach ($typePlugin as $name => $plugin) {
                if (method_exists($plugin, 'getTypeData')) {
                    $typeData[$name] = $plugin->getTypeData($order);
                }
            }
            $order['type_data'] = $typeData;

            // 兼容发货方式
//            try {
//                $order['is_offline'];
//            } catch (\Exception $exception) {
//                $order['is_offline'] = $order['send_type'];
//            }

            $order['template_message_list'] = $this->getTemplateMessage();

            try {
                $order['cancel_data'] = json_decode($order['cancel_data'], true);
            } catch (\Exception $exception) {
                $order['cancel_data'] = [];
            }
            $order['platform'] = '平台自营';
            if ($order['mch_id']) {
                /** @var Mch $mch */
                $mch = Mch::find()->where(['id' => $order['mch_id']])->with('store')->one();
                $order['platform'] = $mch && $mch->store ? $mch->store->name : '未知商户';
                // 客服 @czs
                /** @var MchSetting $setting */
                $setting = MchSetting::find()->where([
                    'mch_id' => $order["mch_id"],
                    "mall_id" => \Yii::$app->mall->id
                ])->one();
                $order["web_service_url"] = $setting->web_service_url;
                $order["is_web_service"] = $setting->is_web_service;
                $order["web_mobile"] = $mch->store->mobile;
            }else{ // @czs 客服
                $order["web_service_url"] = \Yii::$app->mall->getMallSettingOne("web_service_url");
                $order["is_web_service"] = empty($order["web_service_url"]) ? 0 : 1;
                $order["web_mobile"] = \Yii::$app->mall->getMallSettingOne('contact_tel');
            }
            $order['refund_price_text'] = '￥' . $order['total_pay_price'];

            $clerkUser = (new Order())->checkClerkPermission($order['store_id'], \Yii::$app->user->id, $order['mch_id']);
            $order['is_clerk_permission'] = (bool)$clerkUser;

            //预约表单
            $order['order_form'] = \yii\helpers\BaseJson::decode($order['order_form']);
            $order['invoice'] = \Yii::$app->plugin->getPlugin('invoice')->getOrder($order['id']);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $order,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ],
            ];
        }
    }

    // 代付详情
    public function getPayForAnotherDetail()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }

            /** @var PaymentOrderUnion $paymentOrderUnion */
            $paymentOrderUnion = PaymentOrderUnion::find()
                ->where(['id' => $this->id, 'mall_id' => \Yii::$app->mall->id])
                ->with(['paymentOrder' => function($query){
                    $query->with(['order.detail.order', "order.user.userInfo", "order.detail.goods", "order.replaceUser"]);
                }])
                ->one();
            if (!$paymentOrderUnion) {
                throw new \Exception('支付单不存在');
            }

            $data = [
                'id' => $paymentOrderUnion->id,
                'amount' => $paymentOrderUnion->amount,
                'status' => 1, // 待付款
                'payer' => null,
            ];
            $count = 0;
            foreach ($paymentOrderUnion->paymentOrder as $paymentOrder) {
                $order = $paymentOrder->order;
                $data['user'] = [
                    'nickname' => $order->user->nickname,
                    'avatar' => $order->user->userInfo->avatar,
                ];
                $data['auto_cancel_time'] = $order->auto_cancel_time;

                foreach ($order->detail as $item) {
                    $goodsInfo = MallGoods::getGoodsData($item);
                    $data['goods_info'][] = $goodsInfo;
                }

                if(\Yii::$app->user->id == $order->user->id){
                    $data['is_self'] = 1;
                }else{
                    $data['is_self'] = 0;
                }
                if($order->is_pay == 1 || $order->cancel_status == 1){
                    $data['status'] = 0; // 订单已失效
                }
                $count += $order->is_pay == 1 ? 1 : 0;
                if($order->is_pay == 1) {
                    $data['payer'] = $order->replaceUser ? [
                        'nickname' => $order->replaceUser->nickname,
                        'avatar' => $order->replaceUser->userInfo->avatar,
                    ] : $data['user'];
                }
            }
            if($count == count($paymentOrderUnion->paymentOrder)){
                $data['status'] = 2; // 订单已支付
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ],
            ];
        }
    }

    private function getTemplateMessage()
    {
        $arr = ['order_cancel_tpl'];
        $list = TemplateList::getInstance()->getTemplate(\Yii::$app->appPlatform, $arr);
        return $list;
    }
}
