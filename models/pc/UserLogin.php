<?php

namespace app\models\pc;

use app\models\ModelActiveRecord;
use app\models\User;

/**
 * This is the model class for table "{{%pc_user_login}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $user_id 用户ID
 * @property string $token 登录token
 * @property string $ip
 * @property int $expire_time  过期时间戳
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property User $user
 */
class UserLogin extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pc_user_login}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'expire_time'], 'integer'],
            [['mall_id', 'user_id', 'token', 'created_at', 'expire_time', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['token'], 'string', 'max' => 32],
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
            'user_id' => 'user_id',
            'token' => 'token',
            'ip' => 'ip',
            'expire_time' => 'expire_time',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
