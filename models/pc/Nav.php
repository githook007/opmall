<?php

namespace app\models\pc;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%pc_nav}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $name 导航名称
 * @property string $url 导航链接
 * @property string $sort 排序
 * @property string $open_type 打开方式
 * @property int $status 状态：0.隐藏|1.显示
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $is_delete
 */
class Nav extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pc_nav}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['mall_id', 'status', 'is_delete', 'sort'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'open_type'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => '商城 ID',
            'name' => '导航名称',
            'url' => '导航链接',
            'sort' => '排序',
            'open_type' => '链接类型',
            'status' => '导航状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }
}
