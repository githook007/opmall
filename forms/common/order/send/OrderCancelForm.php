<?php

namespace app\forms\common\order\send;

use app\models\Model;
use app\models\Order;
use app\models\OrderDetailExpress;

class OrderCancelForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function cancel()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $express = OrderDetailExpress::find()->andWhere(['id' => $this->id, 'is_delete' => 0])->with(['expressRelation'])->one();
            if (!$express) {
                throw new \Exception('物流订单不存在');
            }
            $express->is_delete = 1;
            $res = $express->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($express));
            }

            foreach ($express->expressRelation as $item) {
                $item->is_delete = 1;
                $res = $item->save();
                if (!$res) {
                    throw new \Exception($this->getErrorMsg($item));
                }
            }

            $order = Order::find()->andWhere(['id' => $express->order_id])->one();
            $order->is_send = 0;
            $res = $order->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($order));
            }

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            \Yii::error($exception);
        }
    }
}
