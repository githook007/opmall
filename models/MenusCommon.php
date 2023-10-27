<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menus_common}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $name 功能名称
 * @property string $url 功能地址
 * @property string $icon icon
 * @property string $created_at
 * @property string $updated_at
 */
class MenusCommon extends ModelActiveRecord
{
    public $isLog = false; // 单独开关
    /**
     * {@menus_common}
     */
    public static function tableName()
    {
        return '{{%menus_common}}';
    }

    /**
     * {@menus_common}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'integer'],
            [['mall_id', 'name', 'url'], 'required'],
            [['name', 'url', 'icon'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@menus_common}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'name' => '功能名称',
            'url' => '功能地址',
            'icon' => 'icon',
        ];
    }
}
