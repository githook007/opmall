<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%payment_order_union}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id
 * @property string $order_no
 * @property string $amount
 * @property int $is_pay 支付状态：0=未支付，1=已支付
 * @property int $pay_type 支付方式：1=微信支付，2=货到付款，3=余额支付，4=支付宝支付，5=百度支付，6=头条支付, 7=微信H5支付，8支付宝H5支付,9.现金支付 10.pos机支付 11.微信付款码支付  12.支付宝付款码支付  13 微信交易组件支付  14. APP支付
 * @property int $is_profit_sharing    1：分账；0：否
 * @property string $title
 * @property string $support_pay_types 支持的支付方式（JSON）
 * @property string $created_at
 * @property string $updated_at
 * @property string $app_version 小程序端版本
 * @property string $platform 平台标识
 * @property string $transaction_id
 * @property PaymentOrder[] $paymentOrder
 * @property User $user
 */
class PaymentOrderUnion extends ModelActiveRecord
{
    /** @var string 交易成功后 */
    const EVENT_TRADE_COMPLETE = 'paymentOrderComplete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_order_union}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'order_no', 'amount', 'title'], 'required'],
            [['mall_id', 'user_id', 'is_pay', 'pay_type', 'is_profit_sharing'], 'integer'],
            [['amount'], 'number'],
            [['support_pay_types', 'transaction_id'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_no', 'app_version', 'platform'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 128],
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
            'user_id' => 'User ID',
            'order_no' => 'Order No',
            'amount' => 'Amount',
            'is_pay' => '支付状态：0=未支付，1=已支付',
            'pay_type' => '支付方式：1=微信支付，2=货到付款，3=余额支付，4=支付宝支付，5=百度支付，6=头条支付, 7=微信H5支付，8支付宝H5支付,9.现金支付 10.pos机支付 11.微信付款码支付  12.支付宝付款码支付',
            'title' => 'Title',
            'support_pay_types' => '支持的支付方式（JSON）',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'app_version' => '小程序端版本',
            'platform' => '平台标识',
            'transaction_id' => '支付单号',
        ];
    }

    public function encodeSupportPayTypes($data)
    {
        return Yii::$app->serializer->encode($data);
    }

    public function decodeSupportPayTypes($data)
    {
        return Yii::$app->serializer->decode($data);
    }

    public function getPaymentOrder()
    {
        return $this->hasMany(PaymentOrder::className(), ['payment_order_union_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
