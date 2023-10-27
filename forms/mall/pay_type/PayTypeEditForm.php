<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/11/4
 * Time: 15:04
 */

namespace app\forms\mall\pay_type;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\PayType;
use app\plugins\wxapp\forms\WechatServicePay;
use app\plugins\wxapp\forms\WechatV3Pay;
use luweiss\Wechat\WechatException;

class PayTypeEditForm extends Model
{
    public $id;
    public $name;
    public $type;
    public $appid;
    public $mchid;
    public $key;
    public $cert_pem;
    public $key_pem;
    public $is_service;
    public $service_key;
    public $service_appid;
    public $service_mchid;
    public $alipay_appid;
    public $app_private_key;
    public $alipay_public_key;
    public $appcert;
    public $alipay_rootcert;

    public $channel;
    public $currency;

    public $tl_merchantId;
    public $v3key;
    public $is_v3;

    public function rules()
    {
        return [
            [['name',], 'required'],
            [['type', 'is_service', 'id', 'currency', 'channel', 'is_v3'], 'integer'],
            [['alipay_public_key', 'appcert', 'alipay_rootcert', 'tl_merchantId'], 'string'],
            [['alipay_public_key', 'appcert', 'alipay_rootcert'], 'default', 'value' => ''],
            [['name', 'appid', 'service_appid', 'service_mchid', 'alipay_appid'], 'string', 'max' => 255],
            [['mchid', 'key', 'service_key', 'v3key'], 'string', 'max' => 32],
            [['cert_pem', 'key_pem', 'app_private_key'], 'string', 'max' => 2000],
            ['app_private_key', function () {
                $this->app_private_key = $this->addBeginAndEnd(
                    '-----BEGIN RSA PRIVATE KEY-----',
                    '-----END RSA PRIVATE KEY-----',
                    $this->app_private_key
                );
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '支付名称',
            'type' => '支付方式',
            'appid' => '微信appid',
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
            'alipay_appid' => '支付宝appid',
            'app_private_key' => '支付宝应用私钥',
            'alipay_public_key' => '支付宝平台公钥',
            'appcert' => '应用公钥证书',
            'alipay_rootcert' => '支付宝根证书',
            'tl_merchantId' => '通联商户号',
            'is_delete' => 'Is Delete',
            'v3key' => '微信支付V3密钥',
            'is_v3' => '提现是否使用v3  1使用老版本  2使用v3',
        ];
    }

    private function addBeginAndEnd($beginStr, $endStr, $data)
    {
        $data = $this->pregReplaceAll('/---.*---/', '', $data);
        $data = trim($data);
        $data = str_replace("\n", '', $data);
        $data = str_replace("\r\n", '', $data);
        $data = str_replace("\r", '', $data);
        $data = wordwrap($data, 64, "\r\n", true);

        if (mb_stripos($data, $beginStr) === false) {
            $data = $beginStr . "\r\n" . $data;
        }
        if (mb_stripos($data, $endStr) === false) {
            $data = $data . "\r\n" . $endStr;
        }
        return $data;
    }

    private function pregReplaceAll($find, $replacement, $s)
    {
        while (preg_match($find, $s)) {
            $s = preg_replace($find, $replacement, $s);
        }
        return $s;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            if($this->channel == 1){
                $this->checkPay();
            }
            if ($this->id) {
                $type = PayType::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id, 'is_delete' => 0]);
                if (!$type) {
                    throw new \Exception('数据异常,该条数据不存在');
                }
            } else {
                $type = new PayType();
                $type->mall_id = \Yii::$app->mall->id;
            }
            $type->attributes = $this->attributes;
            if (!$type->save()) {
                return $this->getErrorResponse($type);
            }
            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
                'id' => $type->id
            ];
        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    /**
     * @throws \Exception
     */
    private function checkPay()
    {
        if ($this->type == 1) {
            $this->checkWxPay();
        } elseif ($this->type == 2) {
            $this->checkAliPay();
        } else {
            throw new \Exception('支付类型错误');
        }
    }

    private function checkWxPay()
    {
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        if (in_array('wxapp', $permission)) {
            // 检测参数是否有效
            if ($this->is_service) {
                $wechatPay = new WechatServicePay([
                    'appId' => $this->service_appid,
                    'mchId' => $this->service_mchid,
                    'sub_appid' => $this->appid,
                    'sub_mch_id' => $this->mchid,
                    'key' => $this->service_key,
                ]);
            } else {
                $wechatPay = new WechatV3Pay([
                    'appId' => $this->appid,
                    'mchId' => $this->mchid,
                    'key' => $this->key
                ]);
            }

            try {
                $wechatPay->orderQuery(['out_trade_no' => '88888888']);
            } catch (WechatException $e) {
                if ($e->getRaw()['return_code'] != 'SUCCESS') {
                    $message = '微信支付商户号 或 微信支付Api密钥有误(' . $e->getRaw()['return_msg'] . ')';
                    throw new \Exception($message);
                }
            }
        }
    }

    private function checkAliPay()
    {
    }
}
