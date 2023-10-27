<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\order;

use Alipay\AopClient;
use Alipay\Key\AlipayKeyPair;
use app\forms\admin\PaySettingForm;

class AppAlipayPay extends PaymentInterface
{
	public function getService()
	{
		$setting = (new PaySettingForm())->getOption();

        return new AopClient(
            $setting['alipay_app_id'],
            AlipayKeyPair::create($setting['alipay_private_key'], $setting['alipay_public_key'])
        );
	}

    public function getNotifyUrl()
    {
        $protocol = env('PAY_NOTIFY_PROTOCOL');
        $url = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/pay-notify/alipay-native.php';
        if ($protocol) {
            $url = str_replace('http://', ($protocol . '://'), $url);
            $url = str_replace('https://', ($protocol . '://'), $url);
        }
        return $url;
    }
}
