<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\mall\wlhulian;

use app\bootstrap\response\ApiCode;
use app\forms\admin\logistics\WlForm;
use app\forms\common\order\send\BaseSend;
use app\forms\wlhulian\api\OrderCreate;
use app\forms\wlhulian\ApiForm;
use app\forms\wlhulian\CommonForm;
use app\forms\wlhulian\GeoTransUtil;
use app\models\Order;
use app\models\OrderDetailExpress;
use app\models\WlhulianWalletLog;

class OrderSendForm extends BaseSend
{
    public $merchant_remark;
    public $delivery;

    /** @var Order */
    private $order;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['delivery'], 'safe'],
            [['merchant_remark'], 'string'],
        ]);
    }

    //GET
    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            // 暂不支持第三方修改配送
            if ($this->express_id) {
                /** @var OrderDetailExpress $orderDetailExpress */
                $orderDetailExpress = OrderDetailExpress::find()->where([
                    'mall_id' => \Yii::$app->mall->id,
                    'id' => $this->express_id,
                ])->one();

                if ($orderDetailExpress->send_type == 1) {
                    throw new \Exception('第三方配送暂不支持修改配送员');
                }
            }

            $this->order = $this->getOrder();
            $this->order = $this->saveOrderDetailExpress($this->order);
            $transaction->commit();

            //触发事件
            if ($this->order->is_send) {
                $this->triggerEvent($this->order);
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '发货成功',
            ];
        }catch (\Exception $e){
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }

    }

    public function saveExtraData($orderDetailExpress)
    {
        $wlHulian = \Yii::$app->mall->wlHulian;
        $deliveryItem = null;
        $maxPrice = 0;
        foreach (CommonForm ::getBillingDetailList($this->order) as $item){
            if(in_array($item['deliveryCode'], $this->delivery)){
                unset($item['status']);
                $deliveryItem[] = $item;
                $maxPrice = max($maxPrice, floatval($item['estimatePrice']));
            }
        }
        if(!$deliveryItem){
            throw new \Exception('配送数据异常');
        }

        if($maxPrice > $wlHulian->balance){
            throw new \Exception('账户余额不足，请前往充值');
        }

        $cityInfo = [
            'city_info' => [
                'id' => $wlHulian->id,
                'name' => '聚合配送',
                'shop_no' => $wlHulian->shop_id,
            ],
            'city_service_info' => $deliveryItem,
        ];

        $object = new OrderCreate();
        $object->outOrderNo = $this->order->order_no;
        $object->multipleSupplierCodes = $this->delivery;
        $object->outShopCode = $wlHulian->shop_id;
        $object->toAddress = $this->order->address;
        $object->toAddressDetail = '-';
        $location = explode(",", $this->order->location);
        $result = GeoTransUtil::gcj_to_bd($location[0], $location[1]);
        $object->toLat = $result['lat'];
        $object->toLng = $result['lng'];
        $object->toReceiverName = $this->order->name;
        $object->toMobile = $this->order->mobile;
        $object->goodType = $wlHulian->industry_type;
        $object->weight = 0;
        foreach ($this->order->detail as $detail) {
            $goodsInfo = \Yii::$app->serializer->decode($detail->goods_info);
            $object->weight += (intval($goodsInfo['goods_attr']['weight'])) * $detail->num;
        }
        $object->weight = intval($object->weight / 1000);
        $object->remarks = $this->order->remark;

        $form = new WlForm();
        $config = $form->getOption();
        $api = new ApiForm($config);
        $api->object = $object;
        $res = $api->request();

        CommonForm::balanceLog($maxPrice, 'sub', WlhulianWalletLog::SEND, $this->order->order_no);

        $orderDetailExpress->shop_order_id = $res['orderNo'];
        $orderDetailExpress->status = 20;

        $orderDetailExpress->city_info = json_encode($cityInfo, JSON_UNESCAPED_UNICODE);
        $orderDetailExpress->city_name = '';
        $orderDetailExpress->city_mobile = '';
        $orderDetailExpress->send_type = 1;
        $orderDetailExpress->city_service_id = 0;
        $orderDetailExpress->merchant_remark = $this->merchant_remark ?: '';

        $orderDetailExpress->express_type = '聚合配送';
    }

    public function checkOrder($order){
        foreach ($order->detail as $detail){
            $this->order_detail_id[] = $detail->id;
        }
        parent::checkOrder($order);
    }
}
