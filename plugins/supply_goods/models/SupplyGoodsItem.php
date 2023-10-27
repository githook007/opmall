<?php

namespace app\plugins\supply_goods\models;

/**
 * This is the model class for table "{{%supply_goods_item}}".
 *
 * @property int $id
 * @property int $mall_id 商城
 * @property int $goods_warehouse_id
 * @property int $supply_id
 * @property float $retail_price     零售价
 * @property float $goods_price      拿货价
 * @property string $attr      规格价
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $is_delete 是否删除
 */
class SupplyGoodsItem extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supply_goods_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'goods_warehouse_id', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['mall_id', 'goods_warehouse_id', 'is_delete', 'supply_id'], 'integer'],
            [['attr'], 'string'],
            [['retail_price', 'goods_price'], 'number'],
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
        ];
    }
}
