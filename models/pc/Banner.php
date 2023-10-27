<?php

namespace app\models\pc;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%pc_banner}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $pic_url 图片
 * @property string $title 标题
 * @property string $page_url 页面路径
 * @property int $sort  排序
 * @property int $is_delete
 * @property string $created_at 创建时间
 * @property string $deleted_at 删除时间
 * @property string $updated_at 修改时间
 */
class Banner extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pc_banner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'is_delete', 'sort'], 'integer'],
            [['mall_id', 'pic_url', 'created_at', 'deleted_at', 'updated_at'], 'required'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['page_url', 'pic_url'], 'string', 'max' => 300],
            [['pic_url', 'page_url'], 'trim'],
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
            'pic_url' => '图片',
            'title' => '标题',
            'page_url' => '页面路径',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
            'created_at' => '创建时间',
            'deleted_at' => '删除时间',
            'updated_at' => '修改时间',
        ];
    }
}
