<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\mall\wlhulian;

use app\bootstrap\response\ApiCode;
use app\forms\wlhulian\CommonForm;
use app\models\Model;
use app\models\Order;

class OrderForm extends Model
{
    public $id;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    //GET
    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try{
            $order = Order::findOne($this->id);
            if(!$order || !$order->location){
                throw new \Exception('订单不支持此配送');
            }
            $wlHulian = \Yii::$app->mall->wlHulian;
            if(!$wlHulian){
                throw new \Exception('订单不支持此配送');
            }
            $sendCount = count($order->detailExpressRelation);

            if(!$sendCount) {
                $list = CommonForm::getBillingDetailList($order);
                foreach ($list as &$item){
                    $item['deliveryChannelName'] .= "（距离：{$item['distance']}；价格：￥{$item['estimatePrice']}）";
                }
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '下单成功',
                'data' => [
                    'setting' => $list ?? [],
                    'sendCount' => $sendCount,
                    'balance' => $wlHulian->balance
                ]
            ];
        }catch (\Exception $e){
            $wlHulian = \Yii::$app->mall->wlHulian;
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'data' => [
                    'balance' => $wlHulian->balance ?? 0
                ]
            ];
        }
    }
}
