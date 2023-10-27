<?php

namespace app\plugins\invoice\models;

use app\models\ModelActiveRecord;

/**
 * This is the model class for table "{{%pond_setting}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property int $switch 开票开关
 * @property string $tax_code 税收商品编码
 * @property string $tax_rate 商品税率
 * @property string $appkey appkey
 * @property string $secretKey secretKey
 * @property string $seller_taxpayer_num 销方纳税人识别号
 * @property string $terminal_code 税盘号
 * @property string $seller_name 销方名称
 * @property string $seller_address 销方地址
 * @property string $seller_tel 销方电话
 * @property string $seller_bank_name 销方银行名称
 * @property string $seller_bank_account 销方银行账号
 * @property string $drawer 开票人姓名
 */
class InvoiceSetting extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['switch', 'tax_code', 'tax_rate', 'mall_id'], 'required'],
            [['switch', 'mall_id'], 'integer'],
            [['tax_rate', 'appkey', 'secretKey', 'seller_taxpayer_num', 'terminal_code', 'seller_name', 'seller_address', 'seller_tel', 'seller_bank_name', 'seller_bank_account', 'drawer'], 'string'],
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

}
