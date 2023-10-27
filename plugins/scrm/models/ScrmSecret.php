<?php

namespace app\plugins\scrm\models;

use Yii;

/**
 * This is the model class for table "{{%scrm_secret}}".
 *
 * @property int $id
 * @property string $app_key
 * @property string $app_secret
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $is_delete
 * @property int $mall_id
 */
class ScrmSecret extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scrm_secret}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'created_at', 'updated_at', 'deleted_at'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['is_delete', 'mall_id'], 'integer'],
            [['app_key', 'app_secret'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_key' => 'App Key',
            'app_secret' => 'App Secret',
            'auth_key' => 'Auth Key',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'mall_id' => 'Mall ID',
        ];
    }
}
