<?php

namespace app\plugins\erp\models;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%erp_order}}".
 *
 * @property int $id
 * @property int $mch_id
 * @property int $mall_id
 * @property int $erp_no    'erp内部单号',
 * @property string $seller_no   '商家订单号',
 * @property string $params    参数
 * @property int $is_delete
 * @property int $is_cancel
 * @property int $is_send
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 * @property string $updated_at 修改时间
 */
class ErpOrder extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%erp_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'mch_id', 'erp_no', 'is_cancel', 'is_send', 'is_delete'], 'integer'],
            [['erp_no', 'created_at', 'deleted_at', 'updated_at'], 'required'],
            [['seller_no', 'params'], 'string'],
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
