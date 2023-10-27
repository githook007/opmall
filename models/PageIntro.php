<?php

namespace app\models;

/**
 * This is the model class for table "{{%page_intro}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $route
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class PageIntro extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_intro}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id'], 'integer'],
            [['route', 'created_at', 'updated_at'], 'required'],
            [['content', 'route'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['route'], 'string', 'max' => 250],
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
            'route' => 'route',
            'content' => 'content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
