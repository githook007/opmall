<?php

namespace app\plugins\wxapp\forms;

use luweiss\Wechat\WechatException;
use yii\db\Exception;

class Communication extends WechatV3Pay
{
    public $channel = 5;
    const NOTIFY_URL = 'communication.php';

    /**
     * 商户配置
     */
    public $storeConfig;
    public $tl_merchantId;

    /**
     * 支付
     * @param array $payData 支付信息
     * @return array 返回数据
     */
    public function unifiedOrder($payData)
    {
        $url = 'https://vsp.allinpay.com/apiweb/unitorder/pay';  // 请求地址

        $data = $this->handleData($payData);  // 处理支付需要的数据
        $res = json_decode($this->post($url, $this->toUrlParams($data)), true);
        if($res['retcode'] == 'SUCCESS' && !empty($res["payinfo"])){
            return json_decode($res['payinfo'], true);
        }else{
            throw new Exception("resultCode:" . $res["retcode"] . "；resultMsg：" . !empty($res["errmsg"]) ? $res["errmsg"] : ($res['retmsg'] ?? ''));
        }
    }

    /**
     * 处理支付需要的数据 -- 支付
     * @param array $payData 支付参数
     * @return array 返回数据
     */
    public function handleData($payData)
    {
        $data = [
            'orgid' => $this->storeConfig['orgid'],  // 代理商参数时必填
            'cusid' => $this->tl_merchantId, // 商户号
            'appid' => $this->storeConfig['tl_appid'], // appid
            'version' => 11,  //版本号，默认11
            'trxamt' => $payData["total_fee"] * 100,  //金额，单位分
            'reqsn' => $payData["out_trade_no"],  // 订单号
            'body' => $payData['body'],  // 标题
            'remark' => '', // 备注
            'validtime' => 15, // 有效时间，分钟单位
            'notify_url' => $payData['notify_url'],  //回调地址
            // 'limit_pay' => 'no_credit', // 不能使用信用卡支付
            'randomstr' => $this->getStr(),  // 随机字符串
            'signtype' => 'RSA', // 签名方式
        ];

        $data['paytype'] = 'W06';
        $data['acct'] = $payData['openid'];  // openid
        $data['sub_appid'] = $this->appId;  // openid
        $data['sign'] = urlencode($this->sign($data, $this->storeConfig['tl_rsaPrivateKey'])); // 签名
        return $data;
    }

    /**
     * post请求
     * @param string $url 请求地址
     * @param string $data 请求数据
     * @return
     */
    public function post($url, $data)
    {
        $ch = curl_init();
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//如果不加验证,就设false,商户自行处理
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);
        curl_close($ch);
        return  $output;
    }

    /**
     * 签名
     * @param array $array 参数
     * @return string
     */
    public function sign(array $array, $rsaPrivateKey)
    {
        ksort($array);
        $bufSignSrc = $this->toUrlParams($array);
        $private_key=$rsaPrivateKey;
        $private_key = chunk_split($private_key , 64, "\n");
        $key = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($private_key)."-----END RSA PRIVATE KEY-----";

        if(openssl_sign($bufSignSrc, $signature, $key )){
            return base64_encode($signature);
        }else{
            echo 'sign fail';
        }
        $sign = base64_encode($signature);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $sign;
    }

    /**
     * 拼接处理参数
     * @param array $array 参数
     * @return string
     */
    public function toUrlParams(array $array)
    {
        $buff = "";
        foreach ($array as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 校验签名
     * @param array $array 参数
     */
    public function validSign(array $array)
    {
        $config = trSetting::getValues(SettingConf::SYSTEM_TL_CONF);
        if(!empty($array['sign'])){
            $sign = $array['sign'];
            unset($array['sign']);
        }else{
            $sign = '';
        }

        ksort($array);
        $bufSignSrc = $this->toUrlParams($array);
        $public_key=$config['rsaPublicKey'];
        $public_key = chunk_split($public_key , 64, "\n");
        $key = "-----BEGIN PUBLIC KEY-----\n$public_key-----END PUBLIC KEY-----\n";
        $result= openssl_verify($bufSignSrc,base64_decode($sign), $key );
        return $result;
    }

    /**
     * 随机32位字符串
     */
    public function getStr(){
        $length = 32;
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;

    }

    /**
     * 当天交易请用撤销,非当天交易才用此退货接口
     */
    function refund($orderData)
    {
        $params = array();
        $params["orgid"] = $this->storeConfig['orgid'];  // 代理商参数时必填
        $params["cusid"] = $this->tl_merchantId;
        $params["appid"] = !empty($this->storeConfig['tl_appid']) ? $this->storeConfig['tl_appid'] : '';
        $params["version"] = 11;
        $params["trxamt"] = $orderData['total_fee'];
        $params["reqsn"] = $orderData['out_refund_no'];
        $params["oldreqsn"] = $orderData['out_trade_no']; //原来订单号
        $params["randomstr"] = $this->getStr();
        $params["signtype"] = "RSA"; //签名类型
        $params["sign"] = urlencode($this->sign($params, $this->storeConfig['tl_rsaPrivateKey'])); //签名
        $paramsStr = $this->toUrlParams($params);
        $url = "https://vsp.allinpay.com/apiweb/tranx/refund";
        $res = json_decode($this->post($url, $paramsStr), true);
        if ($res['retcode'] = 'SUCCESS' && !empty($res['reqsn'])){
            return $res;
        }else{
            throw new WechatException("退款失败:". $res["retmsg"]);
        }
    }
}