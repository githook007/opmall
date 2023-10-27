<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\wlhulian;

use app\forms\admin\logistics\WlForm;
use app\forms\wlhulian\api\OrderBilling;
use app\forms\wlhulian\api\OrderCancel;
use app\forms\wlhulian\api\OrderCourier;
use app\forms\wlhulian\api\OrderDetail;
use app\models\Mall;
use app\models\Order;
use app\models\OrderDetailExpress;
use app\models\WlhulianWalletLog;

class CommonForm
{
    public static function cancel(Order $order){
        $object = new OrderCancel();
        $object->outOrderNo = $order->order_no;
        $object->cancelMessage = '退款';
        $object->cancelType = 20;

        $form = new WlForm();
        $config = $form->getOption();
        $api = (new ApiForm($config));
        $api->object = $object;
        $api->request();
    }

    static public function getBillingDetailList(Order $order): array
    {
        $wlHulian = \Yii::$app->mall->wlHulian;

        $object = new OrderBilling();
        $object->weight = 0;
        foreach ($order->detail as $detail) {
            $goodsInfo = \Yii::$app->serializer->decode($detail->goods_info);
            $object->weight += (intval($goodsInfo['goods_attr']['weight'])) * $detail->num;
        }
        if(!$object->weight){
            throw new \Exception('订单商品重量为0不支持配送');
        }
        $object->weight = intval($object->weight / 1000);
        $object->outOrderNo = $order->order_no;
        $object->outShopCode = $wlHulian->shop_id;
        $object->toAddress = $order->address;
        $object->toAddressDetail = '-';
        $location = explode(",", $order->location);
        $result = GeoTransUtil::gcj_to_bd($location[0], $location[1]);
        $object->toLat = $result['lat'];
        $object->toLng = $result['lng'];
        $object->toReceiverName = $order->name;
        $object->toMobile = $order->mobile;
        $object->goodType = $wlHulian->industry_type;

        $form = new WlForm();
        $config = $form->getOption();
        $api = (new ApiForm($config));
        $api->object = $object;
        $res = $api->request();
        $billingDetailList = $res['billingDetailList'] ?? [];
        if ($wlHulian->price_value <= 0) {
            $res = $form->getPriceOption();
            $wlHulian->price_value = $res['price_value'];
            $wlHulian->price_type = $res['price_type'];
        }
        $list = [];
        foreach ($billingDetailList as $item) {
            if ($item['status'] != 1) {
                continue;
            }
            $item['oldPrice'] = $item['estimatePrice'] = $item['estimatePrice'] / 100;
            if ($wlHulian->price_type == 1) {
                $item['estimatePrice'] += $wlHulian->price_value;
            } else {
                $item['estimatePrice'] += $item['estimatePrice'] * $wlHulian->price_value / 100;
            }
            $item['estimatePrice'] = price_format($item['estimatePrice']);
            $list[] = $item;
        }
        return $list;
    }

    public static function balanceLog($money, $moneyType = 'add', $type = '', $orderNo = '', $userId = ''){
        $wlHulianModel = \Yii::$app->mall->wlHulian;

        $money = abs($money);
        if($moneyType == 'add'){
            $wlHulianModel->balance += $money;
        }elseif($moneyType == 'sub') {
            $wlHulianModel->balance -= $money;
        }else{
            throw new \Exception('异常');
        }
        if(!$wlHulianModel->balance){
            throw new \Exception('余额不足');
        }
        if(!$wlHulianModel->save()){
            \Yii::error('聚合配送操作金额失败：');
            \Yii::error($wlHulianModel->getErrors());
            return true;
        }

        $model = new WlhulianWalletLog();
        $model->mall_id = \Yii::$app->mall->id;
        $model->order_no = strval($orderNo);
        $model->user_id = $userId ?: (\Yii::$app->user->id ?? 0);
        $model->money = $money;
        $model->balance = $wlHulianModel->balance;
        $model->type = $type;
        if(!$model->save()){
            \Yii::error('聚合配送余额变动日志保存失败：');
            \Yii::error($model->getErrors());
        }
        return true;
    }

    public static function msgNotify($data){
        $form = new WlForm();
        $setting = $form->getOption();

        if($setting['appId'] !== $data['appId']){
            return;
        }

//        actualDeliveryTime	2022-03-20 00:00:00	否	Date	完成时间
//        cancelMessage	我不需要配送了	否	String	取消原因
//        cancelTime	2022-03-20 00:00:00	否	Date	取消时间
//        cancelType	1	否	Integer	取消类型[1:个人原因，2:骑手配送不及时，3:骑手无法配送，4:骑手取货不及时，20:其他，21:系统取消]
//        courierMobile	15130333333	否	String	配送员电话
//        courierName	小李	否	String	配送员姓名
//        deliveryChannel	1	是	Integer	配送渠道
//        failMessage	运力方异常	否	String	订单异常原因：配送异常状态下存的
//        finishCode	131241	否	String	收货码(部分快递渠道支持，有则需要展示给用户，无则忽略)
//        orderNo	SS1231414141	是	String	平台订单号
//        outOrderNo	12412413	是	String	外部订单号
//        pickUpTime	2022-03-20 00:00:00	否	Date	取件时间
//        punishAmount	1	否	Integer	取消订单违约金额（单位分）
//        returnPrice	1	否	Integer	退款金额(单位分)
//        returnTime	2022-03-20 00:00:00	否	Date	退款金额到账时间
//        sendStatus	20	是	Integer	订单状态 20-待接单、30取货中、40-配送中、50-已完成、60- 已取消、70- 配送异常
//        takeOrderTime	2022-03-20 00:00:00	否	Date	接单时间
//        thirdPartyOrderNo	124124141	否	String	运力平台订单号

        $body = json_decode($data['data'], true);
        $param = json_decode($body['param'], true);
        \Yii::warning($param);
        $outOrderNo = $param['outOrderNo'];
        $express = OrderDetailExpress::findOne(['is_delete' => 0, 'shop_order_id' => $param['orderNo']]);
        if($express) {
            \Yii::$app->setMall(Mall::findOne($express->mall_id));
            unset($param['orderNo'], $param['outOrderNo']);

            $cityInfo = json_decode($express->city_info, true);
            $city_service_info = $cityInfo['city_service_info'] ?? [];
            $payMoney = 0;
            $price = 0;
            if(!empty($city_service_info[0]['deliveryCode'])){
                foreach ($city_service_info as $item){
                    $payMoney = max($payMoney, $item['estimatePrice']);
                    if($item['deliveryCode'] == $param['deliveryChannel']){
                        $price = $item['estimatePrice'];
                        $city_service_info = array_merge($item, $param);
                    }
                }
            }
            $city_service_info = array_merge($city_service_info, $param);
            $cityInfo['city_service_info'] = $city_service_info;
            if($param['sendStatus'] == 30 && $payMoney != $price){
                self::balanceLog(price_format($payMoney - $price), 'add', WlhulianWalletLog::BACK, $outOrderNo);
            }
            if(in_array($param['sendStatus'], [50, 60, 70]) || $param['cancelType']){
                try{
                    $object = new OrderDetail();
                    $object->outOrderNo = $outOrderNo;
                    $api = new ApiForm($setting);
                    $api->object = $object;
                    $res = $api->request();
                    if($res['orderStatus'] == 5 && $res['returnPrice'] && !in_array($express->status, [60, 70])){ // 退款成功
                        $price = $res['returnPrice'] / 100;
                        if(isset($city_service_info['oldPrice'])){
                            $price += $city_service_info['estimatePrice'] - $city_service_info['oldPrice'];
                        }
                        self::balanceLog($price, 'add', WlhulianWalletLog::BACK, $outOrderNo);
                    }
                }catch (\Exception $e){
                    \Yii::error($e);
                }
            }

            $express->city_info = json_encode($cityInfo, JSON_UNESCAPED_UNICODE);
            $express->city_name = $param['courierName'];
            $express->city_mobile = $param['courierMobile'];
            $express->status = $param['sendStatus'];
            if (!$express->save()) {
                \Yii::error("聚合配送保存数据库失败");
                \Yii::error($express->getErrors());
            }
        }
    }

    static function getInfo($data, $order){
        if(isset($data['sendStatus'])) {
            switch ($data['sendStatus']) {
                case '20':
                    $text = '待接单';
                    break;
                case '30':
                    $text = '取货中';
                    break;
                case '40':
                    $text = '配送中';
                    break;
                case '50':
                    $text = '已完成';
                    break;
                case '60':
                    $text = '已取消';
                    break;
                case '70':
                    $text = '配送异常';
                    break;
                default:
                    $text = '未知';
            }
        }else{
            $text = '待接单';
        }

        $setting = \Yii::$app->mall->getMallSetting(['longitude', 'latitude']);

        try {
            $form = new WlForm();
            $setting = $form->getOption();
            $object = new OrderCourier();
            $object->outOrderNo = $order->order_no;
            $api = new ApiForm($setting);
            $api->object = $object;
            $res = $api->request();
        }catch (\Exception $e){}

        return [
            'status_text' => $text,
            'corporation_name' => $data['deliveryChannelName'] ?? '',
            'corporation_icon' => $data['icon'] ?? '',
            'estimate_time' => '',
            'man_longitude' => $res['lng'] ?? 0,
            'man_latitude' => $res['lat'] ?? 0,
            'shop_longitude' => $setting['longitude'],
            'shop_latitude' => $setting['latitude'],
        ];
    }
}
