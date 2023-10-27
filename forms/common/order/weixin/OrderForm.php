<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\order\weixin;

use app\helpers\ArrayHelper;
use app\helpers\Json;
use app\models\Order;
use app\models\OrderTradeManage;
use app\models\PaymentOrderUnion;

class OrderForm extends BaseForm
{
    /** @var Order */
    public $order;

    /** @var PaymentOrderUnion */
    public $paymentOrderUnion;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['order', 'paymentOrderUnion'], 'safe'],
        ]);
    }

    private static $object;
    public static function getCommon($config = []){
        if(!self::$object){
            self::$object = new self();
        }
        $obj = self::$object;
        $obj->attributes = $config;
        return $obj;
    }

    public function saveData(){
        \Yii::warning('---小程序发货信息管理服务 记录订单---');
        try {
            $res = $this->queryOrder();
            if($res){
               $this->saveOrder($res);
            }
        }catch (\Exception $e){
            if($e->getCode()){
                \Yii::error($e->getMessage());
            }else {
                \Yii::error($e);
            }
        }
    }

    public function saveOrder($res, $model = null){
        if(!$model) {
            $model = OrderTradeManage::findOne(['transaction_id' => $res->transaction_id]);
            if (!$model) {
                $model = new OrderTradeManage();
            }
        }
        $model->attributes = ArrayHelper::toArray($res);
        $model->mall_id = \Yii::$app->mall->id;
        $model->payment_order_union_id = $this->paymentOrderUnion->id;
        $model->trade_create_time = mysql_timestamp($model->trade_create_time);
        $model->pay_time = mysql_timestamp($model->pay_time);
        $model->in_complaint = $model->in_complaint ? 1 : 0;
        $model->shipping = Json::encode($model->shipping);
        if(!$model->save()){
            throw new \Exception($this->getErrorMsg($model));
        }
    }

    public function queryOrder(){
        if(!$this->paymentOrderUnion){
            $this->paymentOrderUnion = $this->order ? $this->order->paymentOrder->paymentOrderUnion : null;
        }
        if(!$this->paymentOrderUnion){
            \Yii::warning('支付单不存在');
            return false;
        }
        if($this->paymentOrderUnion->platform != 'wxapp'){
            \Yii::warning('此平台的订单不需要接入');
            return false;
        }
        if(!$this->isTradeManaged()){
            \Yii::warning('未开通发货信息管理服务');
            return false;
        }
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/sec/order/get_order?access_token={$accessToken}";

        if(!$this->paymentOrderUnion->transaction_id){
            $pay = \Yii::$app->plugin->getPlugin($this->paymentOrderUnion->platform)->getWechatPay();
            $data['merchant_id'] = $pay->mchId;
            if(!empty($pay->sub_mch_id)){
                $data['sub_merchant_id'] = $pay->sub_mch_id;
            }
            $data['merchant_trade_no'] = $this->paymentOrderUnion->order_no;
        }else{
            $data['transaction_id'] = $this->paymentOrderUnion->transaction_id;
        }

        $ret = Json::decode($this->getCurl()->post($url, Json::encode($data))->response, false);
        if ($ret->errcode != 0) {
            $this->errorLog($ret);
        }
        return $ret->order;
    }

    public function getOrderList($page_size = 100, $order_state = '', $last_index = ''){
        if(!$this->isTradeManaged()){
            \Yii::warning('未开通发货信息管理服务');
            return false;
        }
        $accessToken = $this->getAccessToken();
        $data = [
            'page_size' => $page_size
        ];
        if($last_index){
            $data['last_index'] = $last_index;
        }
        if($order_state){ //订单状态枚举：(1) 待发货；(2) 已发货；(3) 确认收货；(4) 交易完成；(5) 已退款。
            $data['order_state'] = intval($order_state);
        }
        $url = "https://api.weixin.qq.com/wxa/sec/order/get_order_list?access_token={$accessToken}";
        $ret = Json::decode($this->getCurl()->post($url, Json::encode($data))->response, false);
        if ($ret->errcode != 0) {
            $this->errorLog($ret);
        }
        return $ret;
    }

    // 校验确认收货
    public function receive()
    {
        \Yii::warning("小程序发货信息管理服务 == 查询订单收货状态");

        try {
            try{
                $orderData = $this->queryOrder();
            }catch (\Exception $e){
                if($e->getCode()){
                    \Yii::error($e->getMessage());
                }else {
                    \Yii::error($e);
                }
                $orderData = [];
            }
            if(!$orderData){
                return;
            }
            // 订单状态枚举：(1) 待发货；(2) 已发货；(3) 确认收货；(4) 交易完成；(5) 已退款。
            if(\Yii::$app->appPlatform == 'wxapp' && $orderData->order_state < 3 &&
                version_compare(\Yii::$app->appVersion, '4.5.1', '>=')){
                throw new \Exception('微信小程序官方后台未确认收货');
            }
            if($this->order){
                $orderTradeManage = $this->order->paymentOrder->orderTradeManage;
                if($orderTradeManage) {
                    $this->saveOrder($orderData, $orderTradeManage);
                }else{
                    \Yii::warning("发货单不存在：订单id：".$this->order->id);
                }
            }
            return;
        } catch (\Exception $exception) {
            \Yii::error($exception);
            throw $exception;
        }
    }
}
