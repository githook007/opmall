<?php

namespace app\models;

/**
 * This is the model class for table "{{%mall_member_goods}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $member_id
 * @property int $goods_warehouse_id
 * @property int $is_delete
 * @property string $created_at
 * @property GoodsWarehouse $goodsWarehouse
 */
class MallMemberGoods extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mall_member_goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'created_at'], 'required'],
            [['mall_id', 'member_id', 'goods_warehouse_id', 'is_delete'], 'integer'],
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

    public function getGoodsWarehouse(){
        return $this->hasOne(GoodsWarehouse::className(), ['id' => 'goods_warehouse_id']);
    }
}
