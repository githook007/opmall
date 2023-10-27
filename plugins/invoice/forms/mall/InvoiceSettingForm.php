<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */

namespace app\plugins\invoice\forms\mall;

use app\bootstrap\response\ApiCode;
use app\plugins\invoice\forms\common\Common;
use app\plugins\invoice\models\InvoiceSetting;
use app\models\Model;

class InvoiceSettingForm extends Model
{
    public $switch;
    public $tax_rate;
    public $tax_code;
    public $appkey;
    public $secretKey;
    public $seller_taxpayer_num;
    public $terminal_code;
    public $seller_name;
    public $seller_address;
    public $seller_tel;
    public $seller_bank_name;
    public $seller_bank_account;
    public $drawer;

    public function rules()
    {
        return [
            [['switch', 'tax_rate', 'tax_code'], 'required'],
            [['switch'], 'integer'],
            [['tax_rate', 'appkey', 'secretKey', 'seller_taxpayer_num', 'terminal_code', 'seller_name', 'seller_address', 'seller_tel', 'seller_bank_name', 'seller_bank_account', 'drawer'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'switch' => '开票开关',
            'tax_code' => '税收商品编码',
            'tax_rate' => '商品税率',
            'appkey' => 'appkey',
            'secretKey' => 'secretKey',
            'seller_taxpayer_num' => '销方纳税人识别号',
            'terminal_code' => '税盘号',
            'seller_name' => '销方名称',
            'seller_address' => '销方地址',
            'seller_tel' => '销方电话',
            'seller_bank_name' => '销方银行名称',
            'seller_bank_account' => '销方银行账号',
            'drawer' => '开票人姓名',
        ];
    }

    public function getList()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => Common::getSetting(),
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };
        if (!$this->appkey || empty($this->appkey)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请填写appkey'
            ];
        }
        if (!$this->secretKey || empty($this->secretKey)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请填写secretKey'
            ];
        }
        if (!$this->seller_taxpayer_num || empty($this->seller_taxpayer_num)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请填写销方纳税人识别号'
            ];
        }
        if (!$this->terminal_code || empty($this->terminal_code)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请填写税盘号'
            ];
        }
        if (!$this->tax_rate || empty($this->tax_rate)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请填写默认费率'
            ];
        }

        $model = InvoiceSetting::findOne([
            'mall_id' => \Yii::$app->mall->id,
        ]);
        if (!$model) {
            $model = new InvoiceSetting();
        }

        $model->attributes = $this->attributes;
        $model->mall_id = \Yii::$app->mall->id;
        if ($model->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
