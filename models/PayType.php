<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pay_type}}".
 *
 * @property int $id
 * @property int $mall_id
 * @property string $name 支付名称
 * @property int $type 1:微信  2:支付宝
 * @property int $channel 1:官方
 * @property int $currency 结算货币；1人民币，2港币，3美元
 * @property string $appid
 * @property string $mchid
 * @property string $key
 * @property string $cert_pem
 * @property string $key_pem
 * @property int $is_service 是否为服务商支付  0否 1是
 * @property string $service_key
 * @property string $service_appid 服务商appid
 * @property string $service_mchid 服务商mch_id
 * @property string $service_cert_pem
 * @property string $service_key_pem
 * @property int $is_auto_add 0否 1是
 * @property string $alipay_appid 支付宝appid
 * @property string $app_private_key 支付宝应用私钥
 * @property string $alipay_public_key 支付宝平台公钥
 * @property string $appcert
 * @property string $alipay_rootcert 支付宝根证书
 * @property string $tl_merchantId
 * @property string $v3key
 * @property int $is_v3
 * @property int $is_delete
 * @property string $updated_at
 * @property string $created_at
 * @property string $deleted_at
 */
class PayType extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pay_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'name', 'updated_at', 'created_at', 'deleted_at', 'is_v3'], 'required'],
            [['mall_id', 'type', 'is_auto_add', 'channel', 'is_service', 'is_delete', 'currency', 'is_v3'], 'integer'],
            [['alipay_public_key', 'appcert', 'alipay_rootcert', 'tl_merchantId', 'v3key'], 'string'],
            [['updated_at', 'created_at', 'deleted_at'], 'safe'],
            [['name', 'appid', 'service_appid', 'service_mchid'], 'string', 'max' => 255],
            [['mchid', 'key', 'service_key', 'alipay_appid'], 'string', 'max' => 32],
            [['cert_pem', 'key_pem', 'service_cert_pem', 'service_key_pem', 'app_private_key'], 'string', 'max' => 2000],
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
            'name' => 'Name',
            'type' => '支付方式',
            'channel' => '支付渠道',
            'currency' => '结算货币',
            'mchid' => '微信支付商户号',
            'key' => '微信支付Api密钥',
            'cert_pem' => '微信支付apiclient_cert.pem',
            'key_pem' => '微信支付apiclient_key.pem',
            'is_service' => '支付类型',
            'service_key' => '服务商Api密钥',
            'service_appid' => '服务商Appid',
            'service_mchid' => '服务商支付商户号',
            'service_cert_pem' => '服务商apiclient_cert.pem',
            'service_key_pem' => '服务商apiclient_key.pem',
            'app_private_key' => '支付宝应用私钥',
            'alipay_public_key' => '支付宝平台公钥',
            'appcert' => '应用公钥证书',
            'alipay_rootcert' => '支付宝根证书',
            'tl_merchantId' => '通联商户号',
            'v3key' => '微信支付V3密钥',
            'is_v3' => '提现是否使用v3  1使用老版本  2使用v3',
            'is_delete' => 'Is Delete',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
