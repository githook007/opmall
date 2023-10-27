<?php

namespace app\plugins\minishop\models;

use app\models\OrderRefund;
use Yii;

/**
 * This is the model class for table "{{%minishop_refund}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $order_id
 * @property int $order_refund_id
 * @property int $aftersale_id
 * @property int $status   1 用户取消售后申请  2 商家处理退款申请中  4	 商家拒绝退款5	商家拒绝退货6	待用户退货7	售后单关闭8	待商家收货11	平台退款中 13	退款成功
 * @property string $aftersale_infos
 * @property OrderRefund $orderRefund
 */
class MinishopRefund extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%minishop_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'order_id', 'order_refund_id', 'aftersale_infos'], 'required'],
            [['mall_id', 'order_id', 'order_refund_id', 'status', 'aftersale_id'], 'integer'],
            [['aftersale_infos'], 'string'],
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
            'order_id' => 'Order ID',
            'order_refund_id' => 'Order Refund ID',
            'aftersale_id' => 'aftersale_id',
            'status' => 'status',
            'aftersale_infos' => 'Aftersale Infos',
        ];
    }

    public function getOrderRefund(){
        return $this->hasOne(OrderRefund::className(), ['id' => 'order_refund_id']);
    }
}
