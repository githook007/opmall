<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\erp\forms\common\data;

use app\models\Model;
use app\models\OrderRefund;
use app\plugins\erp\forms\common\api\ServeHttp;
use app\plugins\erp\forms\common\RequestForm;
use app\plugins\erp\models\ErpOrder;
use app\plugins\erp\models\ErpOrderRefund;
use yii\helpers\Json;

class RefundForm extends Model
{
    /** @var OrderRefund */
    public $refund;

    public function upload(): array
    {
        try{
            $form = RequestForm::getInstance();
            if(!$form->getStatus()){
                \Yii::error('erp已关闭');
                return [];
            }
            $erpOrder = ErpOrder::findOne(['seller_no' => $this->refund->order->order_no]);
            if(!$erpOrder){
                \Yii::warning("erp订单数据不存在");
                return [];
            }
            $params = Json::decode($erpOrder->params);
            $refundData = Json::decode($this->refund->refund_data);
            $goodsInfo = Json::decode($this->refund->detail->goods_info);
            $requestParams = [
                'shop_id' => $params['shop_id'] ?? $form->getApiObj()->shop_id,
                'outer_as_id' => $this->refund->order_no,
                'so_id' => $erpOrder->seller_no,
                'type' => '其它',
                'question_type' => $refundData['cause'],
                'total_amount' => (float)$this->refund->detail->total_price,
                'refund' => (float)$this->refund->refund_price,
                'payment' => 0,
                'items' => [
                    [
                        'sku_id' => (string)$this->refund->detail->goods_id,
                        'qty' => $this->refund->detail->num,
                        'amount' => (float)$this->refund->refund_price,
                        'pic' => $goodsInfo['goods_attr']['cover_pic'],
                        'name' => $goodsInfo['goods_attr']['name'],
                        'properties_value' => [],
                    ]
                ]
            ];
            switch ($this->refund->status){
                case 1: // 待商家处理
                    $requestParams['shop_status'] = 'WAIT_SELLER_AGREE'; // 买家已经申请，等待卖家同意
                    break;
                case 2: // 同意
                    if ($this->refund->type == 3 || $this->refund->is_send == 1) {
                        $requestParams['shop_status'] = 'WAIT_SELLER_CONFIRM_GOODS'; // 买家已经退货，等待卖家确认收货
                    }else {
                        $requestParams['shop_status'] = 'WAIT_BUYER_RETURN_GOODS'; // 卖家已经同意，等待买家退货
                    }
                    break;
                case 3: // 拒绝
                    $requestParams['shop_status'] = 'SELLER_REFUSE_BUYER'; // 卖家拒绝售后
                    break;
                default:
                    $requestParams['shop_status'] = 'WAIT_SELLER_AGREE'; // 买家已经申请，等待卖家同意
            }
            if($this->refund->is_refund == 1){
                $requestParams['shop_status'] = 'SUCCESS'; // 售后成功
            }elseif($this->refund->type == 2 && $this->refund->is_confirm == 1){
                $requestParams['shop_status'] = 'SUCCESS'; // 售后成功
            }
            if($this->refund->is_delete == 1){
                $requestParams['shop_status'] = 'CLOSED'; // 售后关闭
            }
            if ($this->refund->type == 1) {
                $requestParams['type'] = '退货退款';
                $requestParams['items'][0]['type'] = '退货';
            }
            if ($this->refund->type == 2) {
                $requestParams['type'] = '换货';
                $requestParams['items'][0]['type'] = '换货';
            }
            if ($this->refund->type == 3) {
                $requestParams['type'] = '仅退款';
                $requestParams['items'][0]['type'] = '其它';
            }
            foreach ($goodsInfo['attr_list'] as $item){
                $requestParams['items'][0]['properties_value'][] = "{$item['attr_group_name']}：{$item['attr_name']}";
            }
            $requestParams['items'][0]['properties_value'] = implode(", ", $requestParams['items'][0]['properties_value']);
            if($this->refund->remark){
                $requestParams['remark'] = $this->refund->remark;
            }
            if($refundData['goods_status'] == '未收到货'){
                $requestParams['good_status'] = 'BUYER_NOT_RECEIVED'; // 买家未收到货
            }elseif($refundData['goods_status'] == '已收到货'){
                $requestParams['good_status'] = 'BUYER_RECEIVED';
            }
            if($this->refund->is_send == 1 && $this->refund->type != 3){
                $requestParams['good_status'] = 'BUYER_RETURNED_GOODS'; // 买家已退货
            }
            if($this->refund->is_confirm == 1 && $this->refund->type != 3){
                $requestParams['good_status'] = 'SELLER_RECEIVED'; // 卖家已收到退货
            }
            if($this->refund->express){
                $requestParams['logistics_company'] = $this->refund->express;
            }
            if($this->refund->express_no){
                $requestParams['l_id'] = $this->refund->express_no;
            }
            $res = $form->api(ServeHttp::UPLOAD_AFTERSALE, [$requestParams]);
            if($res['code'] == 0){
                $response = $res['data']['datas'][0];
                if(!$response['issuccess']){
                    \Yii::error("订单号{$response['so_id']}售后失败（{$response['msg']}）");
                    return [];
                }
                \Yii::warning($response);
                $model = ErpOrderRefund::findOne(['as_id' => $response['as_id'], 'outer_as_id' => $this->refund->order_no]);
                if(!$model) {
                    $model = new ErpOrderRefund();
                }
                $model->mall_id = \Yii::$app->mall->id;
                $model->mch_id = $form->mch_id;
                $model->params = Json::encode($requestParams);
                $model->as_id = $response['as_id'];
                $model->outer_as_id = $response['outer_as_id'];
                $model->seller_no = (string)$response['so_id'];
                $model->is_delete = $this->refund->is_delete == 1 ? 1 : 0;
                $model->save();
                return $res['data']['datas'];
            }
            \Yii::warning($res);
        }catch (\Exception $e){
            \Yii::warning($e);
        }
        return [];
    }

    public function query(){
        $erpOrder = ErpOrderRefund::findOne(['outer_as_id' => $this->refund->order_no, "is_delete" => 0]);
        if(!$erpOrder){
            \Yii::warning("erp售后订单数据不存在");
            return [];
        }
        $form = RequestForm::getInstance();
        $params = Json::decode($erpOrder->params);
        $requestParams = [
            'shop_id' => $params['shop_id'] ?? $form->getApiObj()->shop_id,
            'as_ids' => [(int)$erpOrder->as_id]
        ];
        $res = $form->api(ServeHttp::QUERY_REFUND_SINGLE, $requestParams);
        if($res['code'] == 0){
            return $res['data']['datas'][0];
        }
        return [];
    }
}
