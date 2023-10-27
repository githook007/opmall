<?php

namespace app\plugins\scrm\models;

use Yii;

/**
 * This is the model class for table "{{%scrm_config}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $appid
 * @property string $secret
 * @property string $domain
 * @property int $is_delete
 */
class ScrmConfig extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scrm_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'required'],
            [['mall_id', 'is_delete'], 'integer'],
            [['appid', 'secret', 'domain'], 'string', 'max' => 255],
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
            'appid' => 'Appid',
            'secret' => 'Secret',
            'domain' => 'Domain',
            'is_delete' => 'Is Delete',
        ];
    }
}
