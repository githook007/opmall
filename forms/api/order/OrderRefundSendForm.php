<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\order;


use app\bootstrap\response\ApiCode;
use app\events\OrderRefundEvent;
use app\models\Model;
use app\models\OrderRefund;

class OrderRefundSendForm extends Model
{
    public $id;
    public $express;
    public $express_no;
    public $customer_name;//京东物流特殊要求字段，商家编码 小程序端用户发货不需要该字段


    public function rules()
    {
        return [
            [['id', 'express', 'express_no'], 'required'],
            [['id'], 'integer'],
            [['express', 'express_no', 'customer_name'], 'string']
        ];
    }

    public function send()
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            /** @var OrderRefund $orderRefund */
            $orderRefund = OrderRefund::find()->where([
                'id' => $this->id,
                'user_id' => \Yii::$app->user->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ])->one();

            if (!$orderRefund) {
                throw new \Exception('订单不存在');
            }

            if ($orderRefund->is_send) {
                throw new \Exception('订单已发货,无需重复操作');
            }

            $orderRefund->customer_name = $this->customer_name;
            $orderRefund->express = $this->express;
            $orderRefund->express_no = $this->express_no;
            $orderRefund->is_send = 1;
            $orderRefund->send_time = date('Y-m-d H:i:s', time());
            $orderRefund->is_confirm = 0;
            $orderRefund->save();

            if (!$orderRefund) {
                throw new \Exception($this->getErrorMsg($orderRefund));
            }
            \Yii::$app->trigger(OrderRefund::EVENT_UPDATE_REFUND, new OrderRefundEvent([
                'order_refund' => $orderRefund
            ])); // @czs

            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '发货成功'
            ];

        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}
