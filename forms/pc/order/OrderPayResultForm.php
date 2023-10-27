<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/2/15 15:55
 */

namespace app\forms\pc\order;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\PaymentOrder;
use app\models\PaymentOrderUnion;

class OrderPayResultForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        $paymentOrderUnion = PaymentOrderUnion::findOne(['id' => $this->id, "user_id" => \Yii::$app->user->id]);
        if (!$paymentOrderUnion) {
            return ["code" => ApiCode::CODE_ERROR, "msg" => "支付订单不存在"];
        }
        if ($paymentOrderUnion->is_pay !== 1) { // 未付款
            return ["code" => ApiCode::CODE_SUCCESS, "data" => ["success" => 0]];
        }
        $paymentOrders = PaymentOrder::findAll(['payment_order_union_id' => $paymentOrderUnion->id]);
        /** @var PaymentOrder $paymentOrder */
        foreach ($paymentOrders as $paymentOrder){
            if($paymentOrder->is_pay !== 1 || $paymentOrder->order->is_pay !== 1){
                return ["code" => ApiCode::CODE_SUCCESS, "data" => ["success" => 0]];
            }
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                "success" => 1
            ],
        ];
    }
}
