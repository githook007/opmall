<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\order;

use app\bootstrap\response\ApiCode;
use app\events\OrderEvent;
use app\forms\common\template\TemplateList;
use app\forms\common\template\order_pay_template\OrderCancelInfo;
use app\models\Model;
use app\models\Order;

class OrderCancelForm extends Model
{
    public $order_id;
    public $remark;
    public $status;
    public $mch_id;

    public function rules()
    {
        return [
            [['order_id', 'status'], 'required'],
            [['order_id', 'status', 'mch_id'], 'integer'],
            [['remark'], 'string'],
        ];
    }

    //后台取消订单
    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transactioan = \Yii::$app->db->beginTransaction();
        try {
            $order = Order::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->order_id,
                'mch_id' => $this->mch_id ?: \Yii::$app->user->identity->mch_id,
                'is_delete' => 0,
                'is_send' => 0,
                'is_sale' => 0,
                'is_confirm' => 0
            ]);

            if (!$order) {
                throw new \Exception('订单不存在');
            }

            if ($order->status == 0) {
                throw new \Exception('订单进行中,不能进行操作');
            }

            if ($order->cancel_status != 2) {
                throw new \Exception('订单已处理');
            }

            // 拒绝
            if ($this->status == 2) {
                $order->cancel_status = 0;
                $order->words = $this->remark;
            }

            // 同意
            if ($this->status == 1) {
                $order->words = $this->remark;
                $order->cancel_status = 1;
                $order->cancel_time = mysql_timestamp();
            }

            if (!$order->save()) {
                throw new \Exception($this->getErrorMsg($order));
            }

            if ($this->status == 1) {
                \Yii::$app->trigger(Order::EVENT_CANCELED, new OrderEvent([
                    'order' => $order
                ]));
            }
            if ($this->status == 2) {
                try {
                    $this->sendTemplate($order);
                } catch (\Exception $exception) {
                    \Yii::error('模板消息发送: ' . $exception->getMessage());
                }
            }
            $transactioan->commit();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功'
            ];
        } catch (\Exception $e) {
            $transactioan->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line'=>$e->getLine(),
                'e'=>$e->getTraceAsString()
            ];
        }
    }

    public function forceCancel()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transactioan = \Yii::$app->db->beginTransaction();
        try {
            $order = Order::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->order_id,
                'mch_id' => $this->mch_id ?: \Yii::$app->user->identity->mch_id,
                'is_delete' => 0,
                'is_send' => 0,
                'is_sale' => 0,
                'is_confirm' => 0
            ]);

            if (!$order) {
                throw new \Exception('订单不存在');
            }

            if ($order->status == 0) {
                throw new \Exception('订单进行中,不能进行操作');
            }

            if ($order->cancel_status == 1) {
                throw new \Exception('订单已取消');
            }

            if($order->is_pay == 1 && $order->paymentOrder->paymentOrderUnion->pay_type == 13){ // @czs 交易组件
                throw new \Exception('自定义交易组件的订单不支持强制取消');
            }

            // 拒绝
            if ($this->status == 2) {
                $order->cancel_status = 0;
                $order->words = $this->remark;
            }

            // 同意
            if ($this->status == 1) {
                $order->words = $this->remark;
                $order->cancel_status = 1;
                $order->cancel_time = mysql_timestamp();
            }

            if (!$order->save()) {
                throw new \Exception($this->getErrorMsg($order));
            }

            if ($this->status == 1) {
                \Yii::$app->trigger(Order::EVENT_CANCELED, new OrderEvent([
                    'order' => $order
                ]));
            }
            
            if ($this->status == 2) {
                $this->sendTemplate($order);
            }

            $transactioan->commit();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功'
            ];
        } catch (\Exception $e) {
            $transactioan->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line'=>$e->getLine(),
                'e'=>$e->getTraceAsString()
            ];
        }
    }

    public function sendTemplate($order)
    {
        try {
            $remark = $order->cancel_status == 1 ? '商家同意取消' : '商家拒绝取消';

            $goodsName = '';
            foreach ($order->detail as $orderDetail) {
                $goodsName .= $orderDetail->goods->name;
            }

            TemplateList::getInstance()->getTemplateClass(OrderCancelInfo::TPL_NAME)->send([
                'goodsName' => $goodsName,
                'order_no' => $order->order_no,
                'price' => $order->total_pay_price,
                'remark' => $remark,
                'user' => $order->user,
                'page' => 'pages/order/index/index?status=2'
            ]);
        } catch (\Exception $exception) {
            \Yii::error('模板消息发送: ' . $exception->getMessage());
        }
    }
}
