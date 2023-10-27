<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%attachment_effect}}".
 *
 * @property int $id
 * @property int $pic_id
 * @property int $effect_id  效果图id
 * @property string $tag     定位标签
 * @property int $is_delete
 * @property string $created_at
 * @property Attachment $attachment
 */
class AttachmentEffect extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%attachment_effect}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pic_id', 'effect_id'], 'required'],
            [['pic_id', 'effect_id', 'is_delete'], 'integer'],
            [['tag'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pic_id' => 'pic_id',
            'effect_id' => 'effect_id',
            'tag' => '定位标签',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
        ];
    }

    public function getAttachment(){
        return $this->hasOne(Attachment::className(), ['id' => 'effect_id']);
    }
}
