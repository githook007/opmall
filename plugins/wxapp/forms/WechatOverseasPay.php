<?php

namespace app\plugins\wxapp\forms;

use Curl\Curl;
use yii\helpers\Json;

// 境外支付，半成品，回调需要整改
class WechatOverseasPay extends WechatV3Pay
{
    public $currency;
    public $channel = 4; // 官方海外微信渠道支付

    const NOTIFY_URL = 'wechat-overseas.php';
    const SIGN_TYPE = 'RSA';

    /**
     * 微信境外支付统一下单
     * https://pay.weixin.qq.com/wiki/doc/api/wxpay/en/fusion_wallet/MiniProgramPay/chapter7_3.shtml
     * https://pay.weixin.qq.com/wiki/doc/api/wxpay/external_v3/ch/fusion_wallet_ch/MiniProgramPay/chapter7_3.shtml
     * https://pay.weixin.qq.com/wiki/doc/api/wxpay_ch/external_v3/fusion_wallet_ch/chapter2_3.shtml#menu3
     * @param array $data
     * @return array
     */
    public function unifiedOrder($data)
    {
        // 结算货币；1人民币，2港币，3美元
        if ($this->currency == 1) {
            $currency_letter = "CNY";
        } elseif ($this->currency == 2) {
            $currency_letter = "HKD";
        } elseif ($this->currency == 3) {
            $currency_letter = "USD";
        } else {
            $currency_letter = "CNY";
        }

        $args = [
            'description' => $data['body'],
            'out_trade_no' => $data['out_trade_no'],
            'amount' => array(
                "total" => $data['total_fee'],
                "currency" => $currency_letter
            ),
            'notify_url' => $data['notify_url'],
            'trade_type' => self::TRADE_TYPE_JSAPI,
            'merchant_category_code' => '5311', // 百货商店
            'payer' => [
                'openid' => $data['openid']
            ],
            'appid' => $this->appId,
            'mchid' => $this->mchId,
        ];

        $api = 'https://api.mch.weixin.qq.com/hk/v3/transactions/jsapi';

        ksort($args);
        $token = $this->getToken($api, Json::encode($args), 'POST');

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json;charset=UTF-8');
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', 'WECHATPAY2-SHA256-RSA2048 ' . $token);
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = $curl->post($api, Json::encode($args));
        if($response->response){
            return Json::decode($response->response);
        }else{
            throw new \Exception($response->error_message);
        }
    }

    /**
     * 生成微信签名文件
     * @param $args
     * @param $signType
     */
    public function makeSign($args, $signType = 'sha256WithRSAEncryption'){
        $message = '';
        if(!empty($args)){
            foreach($args as $v){
                $message .= $v."\n";
            }
        }
        openssl_sign($message, $raw_sign, @file_get_contents($this->keyPemFile), $signType);
        $sign = base64_encode($raw_sign);
        return $sign;
    }

    /**
     *
     * 查询订单 @czs
     * https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_2</a>
     *
     * @param array $args ['out_trade_no']
     * @return array
     */
    public function orderQueryHk($args)
    {
        $api = 'https://api.mch.weixin.qq.com/hk/v3/out-trade-no/'.$args["out_trade_no"];
        return parent::send($api, $args);
    }

    /**
     *
     * 申请退款
     * https://pay.weixin.qq.com/wiki/doc/api/wxpay/external_v3/ch/fusion_wallet_ch/MiniProgramPay/chapter8_2.shtml
     * @param array $args
     * @return array
     */
    public function refund($args)
    {
        if ($this->currency == 1) {
            $currency_letter = "CNY";
        } elseif ($this->currency == 2) {
            $currency_letter = "HKD";
        } elseif ($this->currency == 3) {
            $currency_letter = "USD";
        } else {
            $currency_letter = "CNY";
        }
        $data = [
            'out_trade_no' => $args['out_trade_no'],
            'out_refund_no' => $args['out_refund_no'],
            'amount' => array(
                "refund" => $args['refund_fee'],
                "total" => $args['total_fee'],
                "currency" => $currency_letter
            ),
            'appid' => $this->appId,
            'mchid' => $this->mchId,
        ];

        $api = 'https://api.mch.weixin.qq.com/hk/v3/refunds';

        ksort($data);
        $token = $this->getToken($api, Json::encode($data), 'POST');

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json;charset=UTF-8');
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Authorization', 'WECHATPAY2-SHA256-RSA2048 ' . $token);
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = $curl->post($api, Json::encode($data));
        if($response->response){
            return Json::decode($response->response);
        }else{
            throw new \Exception($response->error_message);
        }
    }
}
