<?php

namespace app\plugins\minishop\models;

use Yii;

/**
 * This is the model class for table "{{%minishop_cat}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $third_cat_id
 * @property string $license
 * @property string $certificate
 * @property int $status 审核状态, 0：审核中，1：审核成功，9：审核拒绝
 * @property string $audit_id 审核单id
 * @property string $reject_reason 如果审核拒绝，返回拒绝原因
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $is_delete
 * @property string $third_cat_name
 */
class MinishopCat extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%minishop_cat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'third_cat_id', 'license', 'certificate', 'created_at'], 'required'],
            [['mall_id', 'third_cat_id', 'status', 'is_delete'], 'integer'],
            [['license', 'certificate'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['audit_id', 'reject_reason', 'third_cat_name'], 'string', 'max' => 255],
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
            'third_cat_id' => 'Third Cat ID',
            'license' => 'License',
            'certificate' => 'Certificate',
            'status' => '审核状态, 0：审核中，1：审核成功，9：审核拒绝',
            'audit_id' => '审核单id',
            'reject_reason' => '如果审核拒绝，返回拒绝原因',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'third_cat_name' => 'Third Cat Name',
        ];
    }
}
