<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plugin_nav}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $plugin_name 插件名
 * @property string $add_time 生成时间
 */
class PluginNav extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plugin_nav}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'plugin_name', 'add_time'], 'required'],
            [['mall_id'], 'integer'],
            [['plugin_name'], 'string'],
            [['add_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'mall ID',
            'plugin_name' => '插件名',
            'add_time' => '生成时间',
        ];
    }
}
