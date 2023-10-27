<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mall_extend}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $goods_limit_num    商品限制数量，-1代表无限制
 * @property int $memory             总内存空间 -1为不限制，单位M
 * @property float $used_memory
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_delete
 */
class MallExtend extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mall_extend}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'goods_limit_num', 'memory', 'is_delete'], 'integer'],
            [['used_memory'], 'number'],
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
            'goods_limit_num' => '商品限制数量',
            'memory' => 'memory',
            'used_memory' => 'used_memory',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_delete' => 'Is Delete',
        ];
    }
}
