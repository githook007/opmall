<?php

namespace app\models;

/**
 * This is the model class for table "{{%wlhulian_data}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $price_type    价格类型；1：固定金额；2：百分比
 * @property float $balance     余额
 * @property float $price_value
 * @property string $shop_id    店铺id
 * @property string $delivery_supplier_list  运力集合
 * @property int $industry_type    行业类型
 * @property int $is_delete
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class WlhulianData extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wlhulian_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'is_delete', 'price_type', 'industry_type'], 'integer'],
            [['balance', 'price_value'], 'number'],
            [['shop_id', 'delivery_supplier_list'], 'string'],
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
            'is_delete' => 'Is Delete',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
