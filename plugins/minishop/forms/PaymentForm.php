<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/18
 * Time: 1:52 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\forms\common\CommonOption;
use app\forms\common\platform\PlatformConfig;
use app\models\Option;
use app\models\PaymentOrderUnion;
use app\plugins\minishop\models\MinishopOrder;
use yii\helpers\Json;

class PaymentForm extends Model
{
    /**
     * @var PaymentOrderUnion $paymentOrderUnion
     */
    public $paymentOrderUnion;

    public function add()
    {
        \Yii::warning('---自定义版交易组件下单---');
        try {
            if (!in_array('minishop', \Yii::$app->mall->role->permission)) {
                \Yii::warning('没权限');
                return [];
            }
            if ($this->paymentOrderUnion->platform !== 'wxapp') {
                \Yii::warning('非微信小程序订单不上传到小商店');
                return [];
            }
            $platformList = PlatformConfig::getInstance()->getPlatformOpenid($this->paymentOrderUnion->user);
            if (!isset($platformList['wxapp'])) {
                \Yii::warning('获取不到微信小程序用户openid不上传到小商店');
                return [];
            }
            $openid = $platformList['wxapp'];
            $form = new CheckForm();
            $plugin = $form->check();
            $shopService = $plugin->getShopService();
            $res = $shopService->register->check();
            if (!in_array($res['data']['status'], [1, 2])) {
                return [];
            }

            $productInfos = [];
            $freight = 0;
            $discountPrice = 0;
            $orderPrice = 0;
            $path = '';
            $minishopOrder = [];
            $order = $this->paymentOrderUnion->paymentOrder[0]->order;
            switch ($order->send_type) { // 0--快递配送 1--到店自提 2--同城配送
                case 1:
                    throw new \Exception('交易组件暂不支持到店自提');
                case 2:
                    $deliveryType = 3;
                    break;
                default:
                    $deliveryType = 1; // 正常快递
            }
            foreach ($this->paymentOrderUnion->paymentOrder as $paymentOrder) {
                $model = MinishopOrder::findOne([
                    'mall_id' => \Yii::$app->mall->id,
                    "platform_order_id" => $paymentOrder->order->id,
                    "status" => 10
                ]);
                $create = true;
                if ($model) {
                    if($model->payment_order_union_no != $this->paymentOrderUnion->order_no) {
                        $res = $shopService->order->close([
                            'order_id' => $model->order_id,
                            'out_order_id' => $model->payment_order_union_no,
                            'openid' => $openid
                        ]);
                        if ($res['errcode'] == 0) {
                            $model->status = 250;
                            $model->save();
                        }
                        if ($model->payment_order_union_id == $this->paymentOrderUnion->id) {
                            $model->delete();
                        }
                    }else{
                        $create = false;
                    }
                }
                $orderPrice += floatval($paymentOrder->order->total_pay_price);
                $freight += floatval($paymentOrder->order->express_price);
                $discountPrice += floatval($paymentOrder->order->total_price - $paymentOrder->order->total_pay_price);
                foreach ($paymentOrder->order->detail as $detail) {
                    $goodsInfo = Json::decode($detail->goods_info);
                    $productInfos[] = [
                        'out_product_id' => $detail->goods_id,
                        'out_sku_id' => $goodsInfo['goods_attr']['id'],
                        'product_cnt' => $detail->num,
                        'sale_price' => intval($detail->unit_price * 100),
                        'sku_real_price' => intval($detail->total_price * 100),
                        'head_img' => $goodsInfo['goods_attr']['pic_url'] ?: $goodsInfo['goods_attr']['cover_pic'],
                        'title' => $goodsInfo['goods_attr']['name'],
                        'path' => $paymentOrder->order->mch_id ?
                            "/plugins/mch/goods/goods?id={$detail->goods_id}&mch_id={$paymentOrder->order->mch_id}" :
                            "/pages/goods/goods?goods_id={$detail->goods_id}"
                    ];
                }
                $path = "/pages/order/order-detail/order-detail?id={$paymentOrder->order->id}";

                if($create) {
                    $model = new MinishopOrder();
                }
                $model->platform_order_id = $paymentOrder->order->id;
                $model->final_price = $paymentOrder->amount;
                $minishopOrder[] = $model;
            }
            $args = [
                'create_time' => mysql_timestamp(),
                'out_order_id' => $this->paymentOrderUnion->order_no,
                'openid' => $openid,
                'path' => $path,
                'order_detail' => [
                    'product_infos' => $productInfos,
                    'pay_info' => [
                        'pay_method_type' => 0, // 0: 微信支付, 1: 货到付款, 2: 商家会员储蓄卡（默认0）
                    ],
                    'price_info' => [
                        'order_price' => intval($orderPrice * 100),
                        'freight' => intval($freight * 100),
                        'discounted_price' => intval($discountPrice * 100),
                        'additional_price' => 0,
                        'additional_remarks' => '',
                    ]
                ],
                'delivery_detail' => [
                    'delivery_type' => $deliveryType
                ],
                'address_info' => [
                    'receiver_name' => $order->name,
                    'detailed_address' => $order->address,
                    'tel_number' => $order->mobile,
                    'country' => '',
                    'province' => '',
                    'city' => '',
                    'town' => '',
                ],
                'fund_type' => 1, // 二级商户单
                'expire_time' => time() + 30 * 60, // 取值：[15min, 1d]
                'trace_id' => ''
            ];
            if($traceData = \Yii::$app->request->get("traceData")){
                \Yii::warning($traceData);
                $traceData = @Json::decode($traceData, true);
                if(isset($traceData['requireOrder']) && $traceData['requireOrder'] == 1){
                    $args['trace_id'] = $traceData['traceId'] ?: '';
                }else{
                    $setting = CommonOption::get('minishop_setting', \Yii::$app->mall->id, Option::GROUP_APP);
                    if(!empty($setting['status']) && !empty($setting['user_id']) && in_array(\Yii::$app->user->id, $setting['user_id'])){
                        $args['trace_id'] = $traceData['traceId'] ?: '';
                    }
                }
            }elseif($traceId = \Yii::$app->request->get("traceId")){
                $args['trace_id'] = $traceId;
            }
            if(!$args['trace_id']) { // 普通单 czs
                $args['fund_type'] = 0;
                $args['order_detail']['pay_info']['prepay_id'] = 'prepay_id';
                $args['order_detail']['pay_info']['prepay_time'] = mysql_timestamp();
            }
            $res = $shopService->order->add($args);
            \Yii::warning($res);

            foreach ($minishopOrder as $model) {
                $model->mall_id = \Yii::$app->mall->id;
                $model->payment_order_union_id = $this->paymentOrderUnion->id;
                $model->payment_order_union_no = $this->paymentOrderUnion->order_no;
                $model->order_id = $res['data']['order_id'] . '';
                $model->data = Json::encode($args);
                $model->status = 10;
                if (!$model->save()) {
                    \Yii::warning($this->getErrorMsg($model));
                }
            }

            if($args['fund_type'] == 1) {
                \Yii::warning('---生成支付参数---');
                $paymentParams = $shopService->order->paymentParams([
                    'out_order_id' => $this->paymentOrderUnion->order_no,
                    'openid' => $openid,
                    'order_id' => $res['data']['order_id'],
                ]);
                return $paymentParams['payment_params'];
            }else{
                return [];
            }
        } catch (\Exception $exception) {
            \Yii::warning($exception);
        }
        return [];
    }
}