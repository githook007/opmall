<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\order\weixin;

use app\models\Mall;
use app\models\PaymentOrderUnion;

class NotifyForm extends BaseForm
{
    public $transaction_id;
    public $merchant_trade_no;
    public $merchant_id;
    public $sub_merchant_id;
    public $msg;
    public $estimated_settlement_time;
    public $confirm_receive_method;
    public $confirm_receive_time;
    public $settlement_time;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['estimated_settlement_time', 'confirm_receive_method', 'settlement_time', 'confirm_receive_time'], 'integer'],
            [['merchant_id', 'sub_merchant_id', 'transaction_id', 'merchant_trade_no', 'msg'], 'string'],
        ]);
    }

    public function remindAccessApi(){
        \Yii::warning("小程序发货信息管理服务 == 提醒接入发货信息管理服务API");
        \Yii::warning($this->attributes);
        if (!$this->validate()) {
            \Yii::error($this->getErrorMsg());
            return false;
        }
    }

    public function remindOrderSettlement(){
        \Yii::warning("小程序发货信息管理服务 == 订单将要结算或已经结算");
        \Yii::warning($this->attributes);
        if (!$this->validate()) {
            \Yii::error($this->getErrorMsg());
            return false;
        }
    }

    public function remindShipping(){
        \Yii::warning("小程序发货信息管理服务 == 提醒需要上传发货信息");
        \Yii::warning($this->attributes);

        if (!$this->validate()) {
            \Yii::error($this->getErrorMsg());
            return false;
        }

        \Yii::warning("暂时没决定好怎么提醒商家，可以用公众号发");
        return true;

        try{
            /** @var PaymentOrderUnion $paymentOrderUnion */
            $paymentOrderUnion = PaymentOrderUnion::find()->where(['order_no' => $this->merchant_trade_no, 'is_pay' => 1])->one();
            if(!$paymentOrderUnion){
                throw new \Exception('支付单不存在');
            }
            \Yii::$app->setMall(Mall::findOne($paymentOrderUnion->mall_id));

        }catch (\Exception $e){
            \Yii::error($e);
        }
        return true;
    }
}
