<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/22
 * Time: 2:15 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\forms\common\platform\PlatformConfig;
use app\helpers\ArrayHelper;
use app\models\OrderRefund;
use app\plugins\minishop\jobs\OrderRefundJob;
use app\plugins\minishop\models\MinishopOrder;
use app\plugins\minishop\models\MinishopRefund;
use yii\helpers\Json;

class RefundForm extends Model
{
    /**
     * @var OrderRefund $refund
     */
    public $refund;

    public $type;

    public $shopService;

    public function getService(){
        if(!$this->shopService) {
            $form = new CheckForm();
            $plugin = $form->check();
            $this->shopService = $plugin->getShopService();
        }
        return $this->shopService;
    }

    public function execute()
    {
        $paymentOrder = $this->refund->order->paymentOrder;
        if($paymentOrder->is_pay != 1 || $paymentOrder->paymentOrderUnion->pay_type != 13){
            \Yii::warning('不是交易组件支付的订单，不进行售后操作，id:' . $this->refund->order_id);
            return true;
        }
        $minihopOrder = MinishopOrder::findOne([
            'payment_order_union_id' => $this->refund->order->paymentOrder->payment_order_union_id,
            'mall_id' => \Yii::$app->mall->id,
            'platform_order_id' => $this->refund->order_id
        ]);
        if (!$minihopOrder) {
            \Yii::warning('未在交易组件中创建订单，不进行后续订单操作' . $this->refund->order->paymentOrder->payment_order_union_id);
            return true;
        }
        $platformList = PlatformConfig::getInstance()->getPlatformOpenid($this->refund->user);

        if (!isset($platformList['wxapp'])) {
            \Yii::warning('获取不到微信小程序用户openid不上传到小商店');
            return true;
        }
        $openid = $platformList['wxapp'];
        $shopService = $this->getService();
        switch ($this->type) {
            case 'create':
                $productInfos = [];
                $data = Json::decode($minihopOrder->data, true);
                foreach ($data['order_detail']['product_infos'] as $value) {
                    if ($value['out_product_id'] == $this->refund->detail->goods_id) {
                        $productInfos[] = [
                            'out_product_id' => $value['out_product_id'],
                            'out_sku_id' => $value['out_sku_id'],
                            'product_cnt' => $value['product_cnt']
                        ];
                    }
                }
                if ($this->refund->type == 1) {
                    $type = 2;
                } elseif ($this->refund->type == 2) {
                    $type = 3;
                } else {
                    $type = 1;
                }
                $args = [
                    'out_order_id' => $minihopOrder->payment_order_union_no,
                    'out_aftersale_id' => $this->refund->order_no,
                    'openid' => $openid,
                    'type' => $type,
                    'create_time' => $this->refund->created_at,
                    'status' => 0,
                    'finish_all_aftersale' => 0,
                    'path' => '/pages/index/index?scene=share&param=',
                    'product_infos' => $productInfos
                ];
                $res = $shopService->sale->add($args);
                \Yii::warning($res);
                $model = new MinishopRefund();
                $model->mall_id = \Yii::$app->mall->id;
                $model->order_id = $this->refund->order_id;
                $model->order_refund_id = $this->refund->id;
                $model->status = $args['status'];
                $model->aftersale_id = 0;
                $model->aftersale_infos = Json::encode($args, JSON_UNESCAPED_UNICODE);
                if (!$model->save()) {
                    throw new \Exception($this->getErrorMsg($model));
                }
                break;
            case 'createNew':
                $this->create($minihopOrder, $openid);
                break;
            case 'update':
                $args = [
                    'out_order_id' => $minihopOrder->payment_order_union_no,
                    'out_aftersale_id' => $this->refund->order_no,
                    'status' => 0,
                    'finish_all_aftersale' => 0,
                ];
                // 用户取消
                if ($this->refund->is_delete == 1) {
                    $args['status'] = 1;
                }
                if ($this->refund->status == 3) {
                    // 商家拒绝售后申请
                    $args['status'] = $this->refund->type == 3 ? 4 : 5;
                } else {
                    // 商家同意售后申请
                    if ($this->refund->type == 3) {
                        //  仅退款 商家退款中
                        $args['status'] = 11;
                    } else {
                        $args['status'] = 6;
                        if ($this->refund->is_send == 1) {
                            $args['status'] = 8;
                        }
                        if ($this->refund->is_confirm == 1) {
                            $args['status'] = 11;
                        }
                    }
                    if ($this->refund->is_refund == 1) {
                        $args['status'] = $this->refund->type == 3 ? 13 : 14;
                    }
                }
                $shopService->sale->update($args);
                $model = MinishopRefund::findOne([
                    'mall_id' => \Yii::$app->mall->id,
                    'order_refund_id' => $this->refund->id,
                    'order_id' => $this->refund->order_id
                ]);
                if ($model) {
                    $model->status = $args['status'];
                    if (!$model->save()) {
                        throw new \Exception($this->getErrorMsg($model));
                    }
                }
                break;
            case 'updateNew':
                $model = MinishopRefund::findOne([
                    'mall_id' => \Yii::$app->mall->id,
                    'order_refund_id' => $this->refund->id,
                    'order_id' => $this->refund->order_id
                ]);
                if (!$model) {
                    \Yii::warning('没有生成售后单');
                    return true;
                }
                $args = ['aftersale_id' => $model->aftersale_id];
                // 用户取消
                if ($this->refund->is_delete == 1) {
                    $args['openid'] = $openid;
                    $shopService->sale->cancel($args);
                    $model->status = 1;
                } elseif ($this->refund->status == 3) { // 商家拒绝售后申请
                    $shopService->sale->reject($args);
                    $model->status = $this->refund->type == 2 ? 5 : 4;
                } else if ($this->refund->status == 2) { // 商家同意售后申请
                    if ($this->refund->is_refund == 1) { // 已退款
                        if($model->status == 13){
                            return true;
                        }
                        $shopService->sale->acceptRefund($args);
                        $model->status = 13;
                    } elseif ($this->refund->is_confirm == 1){
                        return true;
                    } elseif ($this->refund->type != 3) { // 非仅退款
                        if ($this->refund->is_send == 1) {
                            $form = new OrderForm();
                            $list = $form->getCompanyList($shopService);
                            $args['delivery_id'] = $form->tranExpress($list, $this->refund->express);
                            $args['openid'] = $openid;
                            $args['waybill_id'] = $this->refund->express_no;
                            $args['delivery_name'] = $this->refund->express;
                            $shopService->sale->uploadReturnInfo($args);
                            $model->status = 8;
                        }else {
                            $this->refund->refundAddress->address = (array)\Yii::$app->serializer->decode($this->refund->refundAddress->address);
                            $refundAddress = $this->refund->refundAddress->address_detail;
                            $args['address_info'] = [
                                'receiver_name' => $this->refund->refundAddress->name,
                                'detailed_address' => $refundAddress,
                                'tel_number' => $this->refund->refundAddress->mobile,
                                'country' => '中国',
                                'province' => $this->refund->refundAddress->address[0],
                                'city' => $this->refund->refundAddress->address[1],
                                'town' => $this->refund->refundAddress->address[2],
                            ];
                            $shopService->register->updateInfo([
                                'service_agent_type' => [0],
                                'default_receiving_address' => $args['address_info']
                            ]);
                            $shopService->sale->acceptReturn($args);
                            $model->status = 6;
                        }
                    }
                }
                $model->save();
                break;
            default:
        }
        return true;
    }

    public function getEmAfterSalesReason(){
        $data = $this->refund ? (array)\Yii::$app->serializer->decode($this->refund->refund_data) : [];
        if(!isset($data['cause'])){
            return 12;
        }
//        $em = [
//            1 => '拍错/多拍/不喜欢',
//            2 => '多买/买错/不想要',
//            3 => '快递无记录',
//            4 => '少货/空包裹',
//            5 => '已拒签包裹',
//            6 => '快递一直未送达',
//            7 => '商品破损/少件',
//            8 => '质量问题',
//            9 => '商家发错货',
//            10 => '三无产品',
//            11 => '假货',
//            12 => '其他',
//        ];
        $em = [
            8 => '拍错/多拍/不喜欢',
            6 => '多买/买错/不想要',
            7 => '商品破损/少件',
            14 => '质量问题',
            9 => '商家发错货',
            12 => '其他',
        ];
        $res = array_search($data['cause'], $em);
        return $res ?: 12;
    }

    /**
     * @param $minihopOrder
     * @param $openid
     * @throws \Exception
     */
    public function create(MinishopOrder $minihopOrder, $openid){
        $fun = function ($type, $productInfos, $orderId) use ($minihopOrder, $openid) {
            $order_no = $this->refund ? $this->refund->order_no : "RE".date("YmdHis");
            $remark = $this->refund ? $this->refund->remark : "";
            $refundId = $this->refund ? $this->refund->id : 0;
            $args = [
                'out_order_id' => $minihopOrder->payment_order_union_no,
                'out_aftersale_id' => $order_no,
                'openid' => $openid,
                'type' => $type,
                'product_info' => [
                    'out_product_id' => ''.$productInfos['out_product_id'],
                    'out_sku_id' => ''.$productInfos['out_sku_id'],
                    'product_cnt' => $productInfos['product_cnt']
                ],
                "refund_reason" => $remark,
                "refund_reason_type" => $this->getEmAfterSalesReason(),
                'orderamt' => $productInfos['sku_real_price']
            ];
            $res = $this->getService()->sale->addNew($args);
            \Yii::warning($res);
            $model = new MinishopRefund();
            $model->mall_id = \Yii::$app->mall->id;
            $model->order_id = $orderId;
            $model->order_refund_id = $refundId;
            $model->aftersale_id = $res['aftersale_id'] ?? 0;
            $model->status = 0;
            $model->aftersale_infos = Json::encode($args, JSON_UNESCAPED_UNICODE);
            if (!$model->save()) {
                throw new \Exception($this->getErrorMsg($model));
            }
            return $model;
        };

        $data = Json::decode($minihopOrder->data, true);
        $productInfos = ArrayHelper::index($data['order_detail']['product_infos'], "out_product_id");
        if($this->refund){
            if(!isset($productInfos[$this->refund->detail->goods_id])){
                throw new \Exception("售后退款商品找不到");
            }
            if ($this->refund->type == 1) {
                $type = 2;
            } elseif ($this->refund->type == 2) {
                $type = 2;
            } else {
                $type = 1;
            }
            return $fun($type, $productInfos[$this->refund->detail->goods_id], $this->refund->order_id);
        }else{
            $model = [];
            foreach ($minihopOrder->order->detail as $detail){
                if(!isset($productInfos[$detail->goods_id])){
                    throw new \Exception("售后退款商品找不到");
                }
                $model[] = $fun(1, $productInfos[$detail->goods_id], $detail->order_id);
            }
            return $model;
        }
    }

    /**
     * 退款
     * @param $paymentRefund
     * @param $paymentOrderUnion
     * @return bool
     * @throws \Exception
     */
    public function refund($paymentRefund, $paymentOrderUnion)
    {
        if(\Yii::$app->request->post('order_refund_id')) {
            $model = MinishopRefund::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'order_refund_id' => \Yii::$app->request->post('order_refund_id'),
            ]);
            if (!$model) {
                throw new \Exception('交易组件的退款单不存在');
            }
            $minihopOrder = MinishopOrder::findOne([
                'payment_order_union_id' => $paymentOrderUnion->id,
                'mall_id' => \Yii::$app->mall->id,
                'platform_order_id' => $model->order_id
            ]);
            $refundModel[] = $model;
        }elseif(\Yii::$app->request->post('order_id')){
            $minihopOrder = MinishopOrder::findOne([
                'payment_order_union_id' => $paymentOrderUnion->id,
                'mall_id' => \Yii::$app->mall->id,
                'platform_order_id' => \Yii::$app->request->post('order_id')
            ]);
            if (!$minihopOrder) {
                throw new \Exception('未在交易组件中创建订单');
            }
            $platformList = PlatformConfig::getInstance()->getPlatformOpenid($paymentOrderUnion->user);
            $openid = $platformList['wxapp'];
            $refundModel = $this->create($minihopOrder, $openid);
        }

        if($refundModel[0]->status != 13) {
            $this->getService()->sale->acceptRefund(['aftersale_id' => $refundModel[0]->aftersale_id]);
        }
        $refundModel[0]->status = 13;
        if (!$refundModel[0]->save()) {
            throw new \Exception($this->getErrorMsg($refundModel[0]));
        }
        unset($refundModel[0]);
        $i = 1;
        // 退款接口调用时间间隔不能太进，否则报错
        foreach ($refundModel as $k => $refund){
            \Yii::$app->queue->delay(70 * $i)->push(new OrderRefundJob([
                'id' => $refund->id
            ]));
            $i++;
        }
        $paymentRefund->is_pay = 1;
        $paymentRefund->pay_type = 1;
        if (!$paymentRefund->save()) {
            throw new \Exception($this->getErrorMsg($paymentRefund));
        }
        $minihopOrder->status = 250;
        $minihopOrder->save();
        return true;
    }
}