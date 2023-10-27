<?php

namespace app\models;

/**
 * This is the model class for table "{{%user_identity}}".
 *
 * @property int $id 日用户访问
 * @property int $mall_id
 * @property int $visit_uv_new 新增用户留存
 * @property int $visit_uv 活跃用户留存
 * @property int $date
 * @property int $time 时间
 */
class UserVisit extends ModelActiveRecord
{
    public $isLog = false; // 单独开关
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_visit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'visit_uv_new', 'visit_uv', 'time'], 'required'],
            [['mall_id'], 'integer'],
            [['visit_uv_new', 'visit_uv', 'time', 'date'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => '商城id',
            'visit_uv_new' => '新增用户留存',
            'visit_uv' => '活跃用户留存',
            'time' => '时间',
        ];
    }
}
