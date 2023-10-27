<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\erp\forms\common\data;

use app\helpers\ArrayHelper;
use app\models\Express;
use app\plugins\erp\forms\common\api\ServeHttp;
use app\models\Model;
use app\models\Order;
use app\plugins\erp\forms\common\RequestForm;
use app\plugins\erp\models\ErpOrder;
use yii\helpers\Json;

class OrderForm extends Model
{
    /** @var Order[] */
    public $orderList;

    /** @var Order */
    public $order;

    // 订单上传
    public function upload(): array
    {
        try{
            if(!$this->orderList || empty($this->orderList[0])){
                \Yii::error('订单数据为空');
                return [];
            }
            $form = RequestForm::getInstance();
            if(!$form->getStatus()){
                \Yii::error('erp推送已关闭');
                return [];
            }
            if(!$form->getApiObj()->shop_id){
                \Yii::error('没有选择erp店铺');
                return [];
            }

            $params = [];
            // 自研商城系统订单状态：等待买家付款=WAIT_BUYER_PAY，等待卖家发货=WAIT_SELLER_SEND_GOODS（传此状态时实际支付金额即pay节点支付金额=应付金额ERP才会显示已付款待审核）,
            // 等待买家确认收货=WAIT_BUYER_CONFIRM_GOODS,交易成功=TRADE_FINISHED,付款后交易关闭=TRADE_CLOSED,付款前交易关闭=TRADE_CLOSED_BY_TAOBAO；发货前可更新
            foreach ($this->orderList as $order) {
                $address = explode(" ", $order->address);
                $arr = [
                    'shop_id' => $form->getApiObj()->shop_id,
                    'so_id' => $order->order_no,
                    'order_date' => $order->created_at,
                    'shop_status' => 'WAIT_SELLER_SEND_GOODS',
                    'shop_buyer_id' => $order->user->username,
                    'receiver_state' => $address[0],
                    'receiver_city' => $address[1],
                    'receiver_district' => $address[2],
                    'receiver_address' => $address[3],
                    'receiver_name' => $order->name,
                    'receiver_phone' => $order->mobile,
                    'receiver_mobile' => $order->mobile,
                    'pay_amount' => (float)$order->total_pay_price,
                    'freight' => (float)$order->express_price,
                    'pay' => [
                        'outer_pay_id' => $order->paymentOrder->paymentOrderUnion->order_no,
                        'pay_date' => $order->pay_time,
                        'payment' => $order->getPayTypeText(),
                        'seller_account' => '未知',
                        'buyer_account' => $order->user->username,
                        'amount' => (float)$order->paymentOrder->paymentOrderUnion->amount,
                    ],
                ];
                if ($order->remark) {
                    $arr['buyer_message'] = $order->remark;
                }
                if ($order->seller_remark) {
                    $arr['remark'] = $order->seller_remark;
                }
                if ($order->pay_type == 2) {
                    $arr['is_cod'] = true; // 是否货到付款
                }
                foreach ($order->detail as $detail){
                    $goodsInfo = \Yii::$app->serializer->decode($detail->goods_info);
                    $arr['items'][] = [
                        'sku_id' => (string)$goodsInfo['goods_attr']['goods_id'],
                        'shop_sku_id' => (string)$goodsInfo['goods_attr']['goods_id'],
                        'amount' => (float)$detail->total_price,
                        'base_price' => (float)$detail->total_original_price,
                        'qty' => $detail->num,
                        'pic' => $goodsInfo['goods_attr']['cover_pic'],
                        'name' => $goodsInfo['goods_attr']['name'],
                        'outer_oi_id' => \Yii::$app->mall->id . "_{$detail->id}",
                    ];
                }
                $params[] = $arr;
            }
            $res = $form->api(ServeHttp::UPLOAD_ORDERS, $params);
            if($res['code'] == 0){
                $msg = [];
                foreach ($res['data']['datas'] as $k => $response){
                    if(!$response['issuccess']){
                        $msg[] = "订单号{$response['so_id']}失败（{$response['msg']}）";
                        continue;
                    }
                    $model = new ErpOrder();
                    $model->mall_id = \Yii::$app->mall->id;
                    $model->mch_id = $form->mch_id;
                    $model->params = Json::encode($params[$k] ?? []);
                    $model->erp_no = $response['o_id'];
                    $model->seller_no = (string)$response['so_id'];
                    $model->save();
                }
                if($msg){
                    \Yii::error(implode("，", $msg));
                    return [];
                }
                return $res['data']['datas'];
            }
            \Yii::warning($res);
            return [];
        }catch (\Exception $e){
            \Yii::error($e);
            return [];
        }
    }

    // 订单取消
    public function cancel(){
        try {
            if (!$this->orderList || empty($this->orderList[0])) {
                \Yii::error('订单数据为空');
                return;
            }
            $order_no = ArrayHelper::getColumn($this->orderList, "order_no");
            $erpOrderList = ErpOrder::find()->where(["seller_no" => $order_no, "is_delete" => 0])->all();
            if(empty($erpOrderList)){
                return;
            }
            $oIds = [];
            /** @var ErpOrder $erpOrder */
            foreach ($erpOrderList as $erpOrder){
                $oIds[] = (int)$erpOrder->erp_no;
            }
            if($oIds) {
                $cancel_data = Json::decode($this->orderList[0]->cancel_data);
                $res = (RequestForm::getInstance())->api(ServeHttp::CANCEL_ORDERBYOID, [
                    'o_ids' => $oIds,
                    "cancel_type" => $cancel_data['cause'] ?: '不需要了',
                    "remark" => $cancel_data['remark'],
                ]);
                if ($res['code'] != 0) {
                    throw new \Exception($res['msg']);
                }
                ErpOrder::updateAll(['is_cancel' => 1], ["seller_no" => $order_no]);
            }
        }catch (\Exception $e){
            \Yii::error($e);
        }
    }

    // 订单查询
    public function query(): array
    {
        try {
            if (!$this->orderList || empty($this->orderList[0])) {
                \Yii::error('订单数据为空');
                return [];
            }
            $form = RequestForm::getInstance();
            $soIds = [];
            foreach ($this->orderList as $order){
                $soIds[] = $order->order_no;
            }
            $res = $form->api(ServeHttp::QUERY_ORDERS_SINGLE, [
                'so_ids' => $soIds,
            ]);
            if ($res['code'] != 0) {
                throw new \Exception($res['msg']);
            }
            return $res['data'];
        }catch (\Exception $e){
            \Yii::error($e);
            return [];
        }
    }

    // 订单发货
    public function sent()
    {
        try {
            if (!$this->order) {
                \Yii::error('订单数据为空');
                return;
            }
            $erpOrder = ErpOrder::findOne(['seller_no' => $this->order->order_no]);
            if(!$erpOrder){
                \Yii::error('erp订单数据为空');
                return;
            }
            $form = RequestForm::getInstance();
            $params = Json::decode($erpOrder->params);
            $request = [];
            foreach ($this->order->detail as $detail){
                if(!$detail->expressRelation || !$detail->expressRelation->orderExpress){
                    continue;
                }
                $expressList = Express::getExpressList();
                $expressCode = 'xx';
                foreach ($expressList as $value) {
                    if ($value['name'] == $detail->expressRelation->orderExpress->express) {
                        $expressCode = $value['code'];
                        break;
                    }
                }
                $request[] = [
                    'o_id' => (int)$erpOrder->erp_no,
                    'shop_id' => $params['shop_id'] ?? $form->getApiObj()->shop_id,
                    'so_id' => $erpOrder->seller_no,
                    'lc_name' => $detail->expressRelation->orderExpress->express,
                    'l_id' => $detail->expressRelation->orderExpress->express_no,
                    'lc_id' => $expressCode
                ];
            }
            $res = $form->api(ServeHttp::UPLOAD_ORDER_SENT, ['items' => $request]);
            if ($res['code'] != 0) {
                throw new \Exception($res['msg']);
            }
            $erpOrder->is_send = 1;
            $erpOrder->save();
        }catch (\Exception $e){
            \Yii::error($e);
        }
    }
}
