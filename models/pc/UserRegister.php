<?php

namespace app\models\pc;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%pc_user_register}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $token token
 * @property string $data
 * @property int $status
 * @property int $expire_time  过期时间戳
 * @property string $created_at 创建时间
 */
class UserRegister extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pc_user_register}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'expire_time', 'status'], 'integer'],
            [['mall_id', 'token', 'created_at', 'expire_time'], 'required'],
            [['created_at'], 'safe'],
            [['token'], 'string', 'max' => 32],
            [['data'], 'string', 'max' => 5000],
            [['token'], 'trim'],
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
            'token' => 'token',
            'data' => 'data',
            'status' => 'status',
            'expire_time' => 'expire_time',
            'created_at' => '创建时间',
        ];
    }
}
