<?php

namespace app\plugins\wxapp\forms;

use Curl\Curl;
use luweiss\Wechat\WechatPay;
use yii\helpers\Json;

class WechatV3Pay extends WechatPay
{
    public $channel = 1; // 官方

    public $v3key;
    public $is_v3;

    const NOTIFY_URL = 'wechat.php';

    /**
     * @return mixed|null
     * @throws \Exception
     * https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_7_3.shtml
     * 查询余额
     */
    public function balance(){
        $apiUrl = "https://api.mch.weixin.qq.com/v3/merchant/fund/balance/BASIC";
        return Json::decode($this->curlGet($apiUrl));
    }

    /** v3版提现 */
    public function transfers($args){
        try {
            if ($this->is_v3 == 1) {
                return parent::transfers($args);
            } else {
                $api = 'https://api.mch.weixin.qq.com/v3/transfer/batches';
                $request = [
                    'appid' => $this->appId,
                    'out_batch_no' => $args['partner_trade_no'],
                    'total_amount' => intval($args['amount']),
                    'batch_name' => $args['desc'],
                    'batch_remark' => $args['desc'],
                    'total_num' => 1,
                    'transfer_detail_list' => [
                        [
                            'out_detail_no' => $args['partner_trade_no'],
                            'transfer_amount' => intval($args['amount']),
                            'transfer_remark' => $args['desc'],
                            'openid' => $args['openid'],
                        ]
                    ]
                ];
                $header = [];
                if ($request['total_amount'] >= 200000) {
                    $cer = $this->getCertificates();
                    if (!$cer) {
                        throw new \Exception("获取微信平台证书错误");
                    }
                    $request['transfer_detail_list'][0]['user_name'] = $this->getEncrypt($args['name'], $cer['certificates']);
                    $header['Wechatpay-Serial'] = $cer['serial_no'];
                }
                $res = Json::decode($this->curlPost($api, $request, $header));
                if (!empty($res['out_batch_no'])) {
                    \Yii::warning("提现单号：{$request['out_batch_no']} 发起成功了：" . var_export($res, true));
                    return $this->transferBatches($res['out_batch_no']);
                } else {
                    return $this->checkInfo($res, $args);
                }
            }
        } catch (\Exception $e) { // 特殊code处理特殊情况
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function checkInfo($res, $args){
        $msg = !empty($res['message']) ? $res['message'] : '转账失败！';
        if(strpos($msg, "单号已存在但信息不一致") !== false){
            return $this->transferBatches($args['partner_trade_no']);
        }
        throw new \Exception($msg);
    }

    /**
     * @param $out_batch_no
     * @param string $need_query_detail
     * @param int $count
     * @return string|null
     * @throws \Exception
     * https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_5.shtml
     * 商家批次单号查询批次单API
     */
    public function transferBatches($out_batch_no, string $need_query_detail = 'false', int $count = 0){
        $url = "https://api.mch.weixin.qq.com/v3/transfer/batches/out-batch-no/{$out_batch_no}?need_query_detail={$need_query_detail}";
        if($response = $this->curlGet($url)){
            $res = Json::decode($response);
            if(!empty($res['transfer_batch'])){
                if($res['transfer_batch']['batch_status'] == 'FINISHED') {
                    if($res['transfer_batch']['total_num'] == $res['transfer_batch']['success_num']) {
                        return $res['transfer_detail_list'];
                    }else{
                        throw new \Exception("提现失败，请前往商户平台查看原因", $count == 0 ? 100 : 0);
                    }
                }elseif($res['transfer_batch']['batch_status'] == 'CLOSED'){
                    throw new \Exception($res['transfer_batch']['close_reason'] ?? '提现已关闭');
                }else{
                    $count++;
                    if($count > 4){
                        \Yii::warning("最终未成功转账：".var_export($res, true));
                        if($res['transfer_batch']['batch_status'] == 'WAIT_PAY'){
                            throw new \Exception('待商户员工确认付款');
                        }elseif($res['transfer_batch']['batch_status'] == 'ACCEPTED'){
                            throw new \Exception('提现已受理，处理中');
                        }elseif($res['transfer_batch']['batch_status'] == 'PROCESSING'){
                            throw new \Exception('提现转账中');
                        }
                    }
                    sleep(1);
                    return $this->transferBatches($out_batch_no, $need_query_detail, $count);
                }
            }
        }
        throw new \Exception('提现失败');
    }

    public function profitSharingAmounts($out_trade_no){
        $res = $this->orderQuery([
            'out_trade_no' => $out_trade_no,
        ]);
        if(empty($res['transaction_id'])){
            throw new \Exception('订单错误：'.($res['return_msg'] ?? '支付信息有误'));
        }
        $api = "https://api.mch.weixin.qq.com/v3/profitsharing/transactions/{$res['transaction_id']}/amounts";
        $response = $this->curlGet($api);
        return Json::decode($response);
    }

    public function profitSharingResult($out_order_no, $args){
        $api = "https://api.mch.weixin.qq.com/v3/profitsharing/orders/{$out_order_no}";
        $api = $api . "?" . http_build_query($args);
        $response = $this->curlGet($api);
        return Json::decode($response);
    }

    public function profitSharingAdd($args){
        $api = 'https://api.mch.weixin.qq.com/v3/profitsharing/receivers/add';
        $args['appid'] = $this->appId;
        $args['relation_type'] = 'USER';

        $header = [];
        if(isset($args['name'])){
            $cer = $this->getCertificates();
            if (!$cer) {
                throw new \Exception("获取微信平台证书错误");
            }
            $args['name'] = $this->getEncrypt($args['name'], $cer['certificates']);
            $header['Wechatpay-Serial'] = $cer['serial_no'];
        }
        return Json::decode($this->curlPost($api, $args, $header));
    }

    // https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_1.shtml
    public function profitSharing($out_trade_no, $args){
        $res = $this->orderQuery([
            'out_trade_no' => $out_trade_no,
        ]);
        if(empty($res['transaction_id'])){
            throw new \Exception('订单错误：'.($res['return_msg'] ?? '支付信息有误'));
        }

        $api = 'https://api.mch.weixin.qq.com/v3/profitsharing/orders';
        $args['appid'] = $this->appId;
        $args['transaction_id'] = $res['transaction_id'];
        $args['unfreeze_unsplit'] = false;
        $res = Json::decode($this->curlPost($api, $args));
        \Yii::warning("请求分账结果：".var_export($args, true) . var_export($res, true));
        if(!isset($res['state'])){
            throw new \Exception($res['message'] ?? "错误");
        }
        if($res['state'] != 'FINISHED') {
            $count = 0;
            $params = ['transaction_id' => $res['transaction_id']];
            if(isset($args['sub_mchid'])) {
                $params['sub_mchid'] = $args['sub_mchid'];
            }
            do {
                $result = $this->profitSharingResult($res['out_order_no'], $params);
                if(!$result){
                    break;
                }
                if($result['state'] == 'FINISHED'){
                    $res = $result;
                    break;
                }
                $count++;
            } while ($count < 5);
        }
        return $res;
    }

    public function curlPost($api, $args, $header = []){
        ksort($args);
        $args = Json::encode($args);
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json;charset=UTF-8');
        $curl->setHeader('Authorization', 'WECHATPAY2-SHA256-RSA2048 ' . $this->getToken($api, $args, 'POST'));
        if(is_array($header)) {
            foreach ($header as $key => $value) {
                $curl->setHeader($key, $value);
            }
        }
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = $curl->post($api, $args);
        if($response->response){
            return $response->response;
        }else{
            throw new \Exception($response->error_message);
        }
    }

    public function curlGet($api, $header = []){
        $curl = new Curl();
        $curl->setHeader('Authorization', 'WECHATPAY2-SHA256-RSA2048 ' . $this->getToken($api));
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        if(is_array($header)) {
            foreach ($header as $key => $value) {
                $curl->setHeader($key, $value);
            }
        }
        $response = $curl->get($api);
        if($response->response){
            return $response->response;
        }else{
            throw new \Exception($response->error_message);
        }
    }

    /**
     * @param $api string 地址
     * @param $body string 参数，json
     * @param $http_method string 请求方式
     * @return string
     */
    public function getToken($api, $body = '', $http_method = "GET"){
        $nonce_str = md5(uniqid());
        $timestamp = time();
        $url_parts = parse_url($api);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $message = $http_method."\n". $canonical_url."\n". $timestamp."\n". $nonce_str."\n". $body."\n";
        openssl_sign($message, $raw_sign, file_get_contents($this->keyPemFile), 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);

        $resour =  openssl_x509_read(file_get_contents($this->certPemFile));
        $cert_data = openssl_x509_parse($resour);
        $serial_no = $cert_data["serialNumberHex"];
        return sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"', $this->mchId, $nonce_str, $timestamp, $serial_no, $sign);
    }

    /**
     * @param $str
     * @param string $public_key
     * @return string
     * @throws \Exception
     * 加密信息
     */
    public function getEncrypt($str, string $public_key = ''): string
    {
        if(!$public_key) {
            $cer = $this->getCertificates();
            if (!$cer) {
                throw new \Exception("获取微信平台证书错误");
            }
            $public_key = $cer['certificates'];
        }
        if (openssl_public_encrypt($str, $encrypted, $public_key, OPENSSL_PKCS1_OAEP_PADDING)) {
            $sign = base64_encode($encrypted);
        } else {
            throw new \Exception("信息加密错误");
        }
        return $sign;
    }

    /**
     * @return array|false
     * @throws \Exception
     * 获取平台证书
     */
    public function getCertificates(){
        $url = 'https://api.mch.weixin.qq.com/v3/certificates';
        $response = $this->curlGet($url, ['Accept' => 'application/json']);
        $res = Json::decode($response);
        if(!empty($res['data'][0]['encrypt_certificate'])){
            $encrypt_certificate = $res['data'][0]['encrypt_certificate'];
            $certificates['certificates'] = $this->decryptToString($encrypt_certificate['associated_data'], $encrypt_certificate['nonce'], $encrypt_certificate['ciphertext']);
            $certificates['serial_no'] = $res['data'][0]['serial_no'];
            return $certificates;
        }else{
            if(isset($res['message'])){
                throw new \Exception($res['message']);
            }
            return false;
        }
    }

    const AUTH_TAG_LENGTH_BYTE = 16;
    /**
     * Decrypt AEAD_AES_256_GCM ciphertext
     *
     * @param string    $associatedData     AES GCM additional authentication data
     * @param string    $nonceStr           AES GCM nonce
     * @param string    $ciphertext         AES GCM cipher text
     *
     * @return string|bool      Decrypted string on success or FALSE on failure
     */
    public function decryptToString($associatedData, $nonceStr, $ciphertext) {
        $ciphertext = \base64_decode($ciphertext);
        if (strlen($ciphertext) <= self::AUTH_TAG_LENGTH_BYTE) {
            return false;
        }

        // ext-sodium (default installed on >= PHP 7.2)
        if (function_exists('\sodium_crypto_aead_aes256gcm_is_available') && \sodium_crypto_aead_aes256gcm_is_available()) {
            return \sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->v3key);
        }

        // ext-libsodium (need install libsodium-php 1.x via pecl)
        if (function_exists('\Sodium\crypto_aead_aes256gcm_is_available') && \Sodium\crypto_aead_aes256gcm_is_available()) {
            return \Sodium\crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->v3key);
        }

        // openssl (PHP >= 7.1 support AEAD)
        if (PHP_VERSION_ID >= 70100 && in_array('aes-256-gcm', \openssl_get_cipher_methods())) {
            $ctext = substr($ciphertext, 0, -self::AUTH_TAG_LENGTH_BYTE);
            $authTag = substr($ciphertext, -self::AUTH_TAG_LENGTH_BYTE);

            return \openssl_decrypt($ctext, 'aes-256-gcm', $this->v3key, \OPENSSL_RAW_DATA, $nonceStr,
                $authTag, $associatedData);
        }
        throw new \Exception("AEAD_AES_256_GCM需要PHP 7.1以上或者安装libsodium-php");
    }
}
