<?php

namespace app\plugins\app\forms\common;

use app\plugins\wxapp\forms\WechatV3Pay;
use luweiss\Wechat\WechatException;

class WechatPay extends WechatV3Pay
{
    public $sub_appid;
    public $sub_mch_id;

    const NOTIFY_URL = 'wechat-app-pay.php';

    public function unifiedOrder($args)
    {
        $nonceStr = md5(uniqid());
        $args['trade_type'] = self::TRADE_TYPE_APP;
        $args['nonce_str']  = $nonceStr;
        $res = parent::unifiedOrder($args);
        \Yii::warning("app预下单结果：".var_export($res, true));
        $data = [
            'appid' => isset($res['sub_appid']) ? $res['sub_appid'] : $res['appid'],
            'partnerid' => isset($res['sub_mch_id']) ? $res['sub_mch_id'] : $res['mch_id'],
            'prepayid' => $res['prepay_id'],
            'package' => "Sign=WXPay",
            'noncestr' => $nonceStr,
            'timestamp' => time(),
        ];
        $data['sign'] = $this->makeSign($data);
        return $data;
    }

    /**
     * @param $api
     * @param $args
     * @return array
     * @throws WechatException
     */
    public function send($api, $args)
    {
        if($this->sub_appid && $this->sub_mch_id){
            $args['sub_appid'] = $this->sub_appid;
            $args['sub_mch_id'] = $this->sub_mch_id;
        }
        return parent::send($api, $args); // TODO: Change the autogenerated stub
    }

    /**
     * @param $api
     * @param $args
     * @return array
     * @throws WechatException
     */
    protected function sendWithPem($api, $args)
    {
        if($this->sub_appid && $this->sub_mch_id){
            $args['sub_appid'] = $this->sub_appid;
            $args['sub_mch_id'] = $this->sub_mch_id;
        }
        return parent::sendWithPem($api, $args);
    }
}