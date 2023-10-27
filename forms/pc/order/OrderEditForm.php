<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\order;

use app\bootstrap\Plugin;
use app\bootstrap\response\ApiCode;
use app\forms\pc\goods\MallGoods;
use app\forms\common\order\CommonOrderDetail;
use app\models\Model;
use app\models\Order;
use app\models\OrderRefund;
use app\plugins\mch\models\Mch;
use yii\helpers\ArrayHelper;

class OrderEditForm extends Model
{
    public $id; // 订单ID

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
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
//            $form->is_detail = 1;
//            $form->is_goods = 1;
            $form->is_refund = 1;
            $form->is_array = 1;
            $form->is_store = 1;
            $form->relations = ['detail', 'detailExpress'];
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
            foreach ($order['detail'] as $key => &$item) {
                $goodsNum += $item['num'];
                $memberDeductionPriceCount += $item['member_discount_price'];
                $goodsInfo = MallGoods::getGoodsData($item);
                $item['is_show_apply_refund'] = 0;

                $refund = OrderRefund::find()->andWhere(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'order_detail_id' => $item['id']])->orderBy('id DESC')->one();

                if (!$refund && $order['is_sale'] == 0) {
                    $item['is_show_apply_refund'] = 1;
                }
                $item['refund'] = $refund ? ArrayHelper::toArray($refund) : null;
                if ($item['refund']) {
                    // 售后订单 状态
                    $item['refund']['status_text'] = $orderRefund->statusText($item['refund']);
                    $refundCount = OrderRefund::find()->andWhere(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'order_detail_id' => $item['id']])->count();
                    // 售后被拒绝后可再申请一次
                    if ($refund->status == 3 && $refundCount == 1) {
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
            }
            unset($detailExpress);
            $order['merchant_remark_list'] = $merchantRemarkList;

            // 订单状态
            $order['status_text'] = (new Order())->orderStatusText($order);
            $order['pay_type_text'] = (new Order())->getPayTypeText($order['pay_type']);
            // 订单商品总数
            $order['goods_num'] = $goodsNum;
            $order['member_deduction_price_count'] = price_format($memberDeductionPriceCount);

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
            } catch (\Exception $exception) {}

            try {
                $order['cancel_data'] = \Yii::$app->serializer->decode($order['cancel_data']);
            } catch (\Exception $exception) {
                $order['cancel_data'] = [];
            }
            $order['platform'] = \Yii::$app->mall->name ? \Yii::$app->mall->name : '平台自营';
            if ($order['mch_id']) {
                /** @var Mch $mch */
                $mch = Mch::find()->where(['id' => $order['mch_id']])->with('store')->one();
                $order['platform'] = $mch && $mch->store ? $mch->store->name : '未知商户';
            }
            $order['refund_price_text'] = '￥' . $order['total_pay_price'];

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => $order,
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
