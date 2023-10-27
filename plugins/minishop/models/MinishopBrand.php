<?php

namespace app\plugins\minishop\models;

use Yii;

/**
 * This is the model class for table "{{%minishop_brand}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $brand_id
 * @property int $status 审核状态 0：审核中，1：审核成功，9：审核拒绝
 * @property string $license 营业执照
 * @property int $brand_audit_type 认证审核类型1--国内品牌申请-R标 2--国内品牌申请-TM标 3--海外品牌申请-R标 4--海外品牌申请-TM标
 * @property string $trademark_type 商标分类1～45
 * @property int $brand_management_type 经营类型 1--自有品牌 2--代理品牌 3--无品牌
 * @property int $commodity_origin_type 是否进口 1--是 2--否
 * @property string $brand_wording 商标/品牌词
 * @property string $sale_authorization 销售授权书
 * @property string $trademark_registration_certificate 商标注册证书
 * @property string $trademark_registrant 商标注册人姓名
 * @property string $trademark_registrant_nu 商标注册号/申请号
 * @property string $trademark_authorization_period 商标有效期
 * @property string $trademark_registration_application 商标注册申请受理通知书
 * @property string $trademark_applicant 商标申请人姓名
 * @property string $trademark_application_time 商标申请时间
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $is_delete
 * @property string $audit_id 审核单id
 * @property string $reject_reason 审核结果
 */
class MinishopBrand extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%minishop_brand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'status', 'brand_audit_type', 'brand_management_type', 'commodity_origin_type', 'is_delete'], 'integer'],
            [['license', 'created_at'], 'required'],
            [['license', 'sale_authorization', 'trademark_registration_certificate', 'trademark_registration_application'], 'string'],
            [['trademark_authorization_period', 'trademark_application_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['brand_id', 'trademark_type', 'brand_wording', 'trademark_registrant', 'trademark_registrant_nu', 'trademark_applicant', 'audit_id', 'reject_reason'], 'string', 'max' => 255],
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
            'brand_id' => 'Brand ID',
            'status' => '审核状态 0：审核中，1：审核成功，9：审核拒绝',
            'license' => '营业执照',
            'brand_audit_type' => '认证审核类型1--国内品牌申请-R标 2--国内品牌申请-TM标 3--海外品牌申请-R标 4--海外品牌申请-TM标',
            'trademark_type' => '商标分类1～45',
            'brand_management_type' => '经营类型 1--自有品牌 2--代理品牌 3--无品牌',
            'commodity_origin_type' => '是否进口 1--是 2--否',
            'brand_wording' => '商标/品牌词',
            'sale_authorization' => '销售授权书',
            'trademark_registration_certificate' => '商标注册证书',
            'trademark_registrant' => '商标注册人姓名',
            'trademark_registrant_nu' => '商标注册号/申请号',
            'trademark_authorization_period' => '商标有效期',
            'trademark_registration_application' => '商标注册申请受理通知书',
            'trademark_applicant' => '商标申请人姓名',
            'trademark_application_time' => '商标申请时间',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
            'audit_id' => '审核单id',
            'reject_reason' => '审核结果',
        ];
    }
}
