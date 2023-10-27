<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/11 14:49
 */


namespace app\controllers\api\notices;


use app\bootstrap\payment\PaymentNotify;
use app\bootstrap\payment\PaymentOrder;

class TestBuyNotify extends PaymentNotify
{

    /**
     * @param PaymentOrder $paymentOrder
     * @return mixed
     */
    public function notify($paymentOrder)
    {
        \Yii::warning('支付结果通知：' . \Yii::$app->serializer->encode($paymentOrder->attributes));
    }
}
