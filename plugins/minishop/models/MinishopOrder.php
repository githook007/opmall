<?php

namespace app\plugins\minishop\models;

use app\models\Order;
use Yii;

/**
 * This is the model class for table "{{%minishop_order}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $payment_order_union_id
 * @property int $platform_order_id
 * @property string $payment_order_union_no
 * @property string $order_id 交易组件平台订单id
 * @property string $final_price 订单最终价格（单位：分）
 * @property int $status 订单状态10-待付款20-待发货30--待收货100--已完成200--全部商品售后之后，订单取消250--用户主动取消/待付款超时取消/商家取消1010--用户已付定金
 * @property string $data
 * @property Order $order
 */
class MinishopOrder extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%minishop_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'payment_order_union_id', 'final_price', 'status'], 'required'],
            [['mall_id', 'payment_order_union_id', 'status', 'platform_order_id'], 'integer'],
            [['final_price'], 'number'],
            [['data', 'payment_order_union_no'], 'string'],
            [['order_id'], 'string', 'max' => 255],
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
            'payment_order_union_id' => 'Payment Order Union ID',
            'order_id' => '交易组件平台订单id',
            'final_price' => '订单最终价格（单位：分）',
            'status' => '订单状态10-待付款20-待发货30--待收货100--已完成200--全部商品售后之后，订单取消250--用户主动取消/待付款超时取消/商家取消1010--用户已付定金',
            'data' => 'Data',
        ];
    }

    public function getOrder(){
        return $this->hasOne(Order::className(), ['id' => 'platform_order_id']);
    }
}
