<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/29
 * Time: 9:19 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\Brand;

use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopBrand;
use yii\helpers\Json;

class BrandEditForm extends Model
{
    public $id;
    public $license;
    public $brand_audit_type;
    public $trademark_type;
    public $brand_management_type;
    public $commodity_origin_type;
    public $brand_wording;
    public $sale_authorization;
    public $trademark_registration_certificate;
    public $trademark_registrant;
    public $trademark_registrant_nu;
    public $trademark_authorization_period;
    public $trademark_registration_application;
    public $trademark_applicant;
    public $trademark_application_time;

    public function rules()
    {
        return [
            [['brand_wording', 'license', 'trademark_registrant_nu'], 'required'],
            [['id', 'commodity_origin_type', 'brand_audit_type', 'brand_management_type'], 'integer'],
            [['license', 'trademark_type', 'brand_wording', 'trademark_registration_certificate',
                'trademark_registrant', 'trademark_registrant_nu', 'trademark_authorization_period',
                'trademark_registration_application', 'trademark_applicant', 'trademark_application_time'], 'trim'],
            [['license', 'trademark_type', 'brand_wording', 'trademark_registration_certificate',
                'trademark_registrant', 'trademark_registrant_nu', 'trademark_authorization_period',
                'trademark_registration_application', 'trademark_applicant', 'trademark_application_time'], 'string'],
            [['sale_authorization'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'license' => '营业执照',
            'brand_audit_type' => '认证审核类型',
            'trademark_type' => '商标分类',
            'brand_management_type' => '经营类型',
            'commodity_origin_type' => '是否进口',
            'brand_wording' => '品牌名称',
            'sale_authorization' => '销售授权书',
            'trademark_registration_certificate' => '商标注册证书',
            'trademark_registrant' => '商标注册人姓名',
            'trademark_registrant_nu' => '商标注册号/申请号',
            'trademark_authorization_period' => '商标有效期',
            'trademark_registration_application' => '商标注册申请受理通知书',
            'trademark_applicant' => '商标申请人姓名',
            'trademark_application_time' => '商标申请时间',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $minishopBrand = MinishopBrand::findOne([
                'id' => $this->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
            ]);
            if (!$minishopBrand) {
                $minishopBrand = new MinishopBrand();
                $minishopBrand->mall_id = \Yii::$app->mall->id;
                $minishopBrand->is_delete = 0;
                $minishopBrand->status = 0;
            }
            if ($minishopBrand->status == 1) {
                throw new \Exception('品牌已通过审核，无需更改');
            }
            $minishopBrand->attributes = $this->attributes;
            $minishopBrand->status = 0;
            if (!$this->sale_authorization) {
                $this->sale_authorization = [];
            }
            $minishopBrand->sale_authorization = Json::encode($this->sale_authorization, JSON_UNESCAPED_UNICODE);
            $common = CommonRegister::getInstance();
            $res = $common->shopService->register->auditBrand($this->attributes);
            $minishopBrand->audit_id = $res['audit_id'];
            if (!$minishopBrand->save()) {
                throw new \Exception($this->getErrorMsg($minishopBrand));
            }
            return $this->success([
                'msg' => '上传成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
