<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\order\weixin;

use app\forms\common\platform\PlatformConfig;
use app\helpers\Json;
use app\models\Express;
use app\models\Order;

class ShipForm extends BaseForm
{
    /** @var Order */
    public $order;

    /**
     * @var integer 物流模式，发货方式枚举值：1、实体物流配送采用快递公司进行实体物流配送形式 2、同城配送 3、虚拟商品，虚拟商品，例如话费充值，点卡等，无实体配送形式 4、用户自提
     */
    public $send_type;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['order'], 'required'],
            [['send_type'], 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'order' => '订单',
            'send_type' => '发货方式',
        ];
    }

    private static $object;
    public static function getCommon($config){
        if(!self::$object){
            self::$object = new self();
        }
        $obj = self::$object;
        $obj->attributes = $config;
        return $obj;
    }

    /**
     * @return int
     */
    private function sendType(){
        //
        if($this->order->send_type == 0){
            return 1;
        } elseif ($this->order->send_type == 1){
            return 4;
        } elseif ($this->order->send_type == 2){
            return 2;
        } else {
            return 3;
        }
    }

    //发货
    public function save()
    {
        \Yii::warning("小程序发货信息管理服务 == 发货信息录入接口");

        try {
            if (!$this->validate()) {
                \Yii::warning($this->getErrorMsg());
                return;
            }
            $orderTradeManage = $this->order->paymentOrder->orderTradeManage;
            if(!$orderTradeManage){
                \Yii::warning('待发货单不存在');
                return;
            }
            $paymentOrderUnion = $this->order->paymentOrder->paymentOrderUnion;
            if(!$paymentOrderUnion){
                \Yii::warning('支付单不存在');
                return;
            }
            if($paymentOrderUnion->platform != 'wxapp'){
                \Yii::warning('此平台的订单不需要接入');
                return;
            }
            if(!$this->isTradeManaged()){
                \Yii::warning('未开通发货信息管理服务');
                return;
            }
            $this->send_type = $this->sendType();
            $orderKey = [
                'order_number_type' => $paymentOrderUnion->transaction_id ? 2 : 1, // 枚举值1，使用下单商户号和商户侧单号；枚举值2，使用微信支付单号。
            ];
            if($orderKey['order_number_type'] == 1){
                $pay = \Yii::$app->plugin->getPlugin($paymentOrderUnion->platform)->getWechatPay();
                $orderKey['mchid'] = $pay->mchId;
                $orderKey['out_trade_no'] = $paymentOrderUnion->order_no;
            }else{
                $orderKey['transaction_id'] = $paymentOrderUnion->transaction_id;
            }
            $platformList = PlatformConfig::getInstance()->getPlatformOpenid($this->order->user);
            $openid = $platformList['wxapp'] ?? '';
            $data = [
                'order_key' => $orderKey,
                'logistics_type' => $this->send_type,
                'delivery_mode' => 1, // 1、UNIFIED_DELIVERY（统一发货）2、SPLIT_DELIVERY（分拆发货）
                'shipping_list' => [],
                'upload_time' => date(DATE_RFC3339),
                'payer' => ['openid' => $openid]
            ];
            if($this->order->is_send == 1 && count($this->order->detailExpress) == 1){
                $data['delivery_mode'] = 1;
            }else{
                $data['delivery_mode'] = 2;
                $data['is_all_delivered'] = false;
                if($this->order->is_send == 1){
                    $data['is_all_delivered'] = true;
                }
            }
            $shipData = [];
            foreach ($this->order->detail as $detail){
                if(!$detail->expressRelation || !$detail->expressRelation->orderExpress){
                    continue;
                }
                $detailExpress = $detail->expressRelation->orderExpress;
                $k = $detailExpress->id;
                $goodsInfo = Json::decode($detail->goods_info);
                $shipData[$k]['item_desc'] = isset($shipData[$k]) ? $shipData[$k]['item_desc'] . ",{$goodsInfo['goods_attr']['name']}" : $goodsInfo['goods_attr']['name'];
                if($this->send_type == 1) {
                    $shipData[$k]['tracking_no'] = $detailExpress->express_no;
                    $shipData[$k]['express_company'] = $this->getDeliveryList($detailExpress->express);
                }
                $shipData[$k]['contact'] = ['receiver_contact' => $this->order->mobile];
            }
            $data['shipping_list'] = array_values($shipData);
            $accessToken = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token={$accessToken}";
            $ret = Json::decode($this->getCurl()->post($url, Json::encode($data))->response, false);
//            \Yii::warning($data);
            if ($ret->errcode != 0) {
                $this->errorLog($ret);
            }
            if($this->order->is_send == 1){
                $orderTradeManage->order_state = 2;
                if(!$orderTradeManage->save()){
                    throw new \Exception($this->getErrorMsg($orderTradeManage));
                }
            }
        } catch (\Exception $exception) {
            if($exception->getCode()){
                \Yii::error($exception->getMessage());
            }else {
                \Yii::error($exception);
            }
        }
    }

    private $deliveryList;
    public function getDeliveryList($express){
        if(!$this->deliveryList) {
            $accessToken = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/express/delivery/open_msg/get_delivery_list?access_token={$accessToken}";
            $ret = Json::decode($this->getCurl()->post($url, '{}')->response, false);
            if ($ret->errcode == 0) {
                $this->deliveryList = array_column($ret->delivery_list, "delivery_id", "delivery_name");
            } else {
                $this->errorLog($ret);
            }
        }
        if(isset($this->deliveryList[$express])){
            return $this->deliveryList[$express];
        }else{
            return Express::getOne($express)['code'] ?? '';
        }
    }
}
