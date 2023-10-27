<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mall_no_more_notice}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $notice_id 公告id
 * @property string $add_time 生成世界
 */
class MallNoMoreNotice extends ModelActiveRecord
{
    /**
     * {@mall_no_more_notice}
     */
    public static function tableName()
    {
        return '{{%mall_no_more_notice}}';
    }

    /**
     * {@menus_common}
     */
    public function rules()
    {
        return [
            [['mall_id', 'notice_id'], 'integer'],
            [['mall_id', 'notice_id', 'add_time'], 'required'],
            [['add_time'], 'safe'],
        ];
    }

    /**
     * {@mall_no_more_notice}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'notice_id' => '公告id',
            'add_time' => '生成世界',
        ];
    }
}
