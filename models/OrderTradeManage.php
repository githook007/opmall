<?php

namespace app\models;

/**
 * This is the model class for table "{{%order_trade_manage}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $payment_order_union_id
 * @property string $transaction_id 微信交易单号
 * @property string $merchant_trade_no 商户订单号
 * @property int $order_state 订单状态枚举：(1) 待发货；(2) 已发货；(3) 确认收货；(4) 交易完成；(5) 已退款。
 * @property string $description 商品描述
 * @property string $merchant_id 支付商户号
 * @property string $sub_merchant_id 二级商户号
 * @property string $trade_create_time 会员优惠金额(正数表示优惠，负数表示加价)
 * @property string $openid 支付者openid
 * @property string $pay_time 支付时间
 * @property string $shipping 发货信息
 * @property int $is_delete
 * @property string $created_at
 * @property string $updated_at
 * @property int $in_complaint 是否处在交易纠纷中。0否
 * @property PaymentOrderUnion $paymentOrderUnion
 */
class OrderTradeManage extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_trade_manage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'payment_order_union_id', 'order_state', 'created_at', 'updated_at'], 'required'],
            [['mall_id', 'payment_order_union_id', 'order_state', 'is_delete', 'in_complaint'], 'integer'],
            [['description', 'merchant_id', 'sub_merchant_id', 'trade_create_time', 'pay_time', 'openid',
                'shipping', 'transaction_id', 'merchant_trade_no'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'payment_order_union_id' => '支付id',
            'order_state' => '订单状态枚举',
            'description' => '商品描述',
            'merchant_id' => '支付商户号',
            'sub_merchant_id' => '二级商户号',
            'trade_create_time' => '交易创建时间',
            'openid' => '支付者openid',
            'pay_time' => '支付时间',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'in_complaint' => '是否处在交易纠纷中',
            'shipping' => '发货信息',
        ];
    }

    public function getPaymentOrderUnion()
    {
        return $this->hasOne(PaymentOrderUnion::className(), ['id' => 'payment_order_union_id']);
    }
}
