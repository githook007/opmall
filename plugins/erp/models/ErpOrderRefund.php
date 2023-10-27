<?php

namespace app\plugins\erp\models;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%erp_order_refund}}".
 *
 * @property int $id
 * @property int $mch_id
 * @property int $mall_id
 * @property int $as_id    'erp内部售后单号',
 * @property string $seller_no   '商家订单号',
 * @property string $outer_as_id   '外部售后单号',
 * @property string $params    参数
 * @property int $is_delete
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 * @property string $updated_at 修改时间
 */
class ErpOrderRefund extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%erp_order_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'mch_id', 'as_id', 'is_delete'], 'integer'],
            [['as_id', 'created_at', 'deleted_at', 'updated_at'], 'required'],
            [['seller_no', 'outer_as_id', 'params'], 'string'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
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
            'is_delete' => 'Is Delete',
            'created_at' => '创建时间',
            'deleted_at' => '删除时间',
            'updated_at' => '修改时间',
        ];
    }
}
