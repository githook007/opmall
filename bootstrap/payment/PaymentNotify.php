<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/11 11:32
 */


namespace app\bootstrap\payment;


use yii\base\Component;


abstract class PaymentNotify extends Component
{
    /**
     * @param PaymentOrder $paymentOrder
     * @return mixed
     */
    abstract public function notify($paymentOrder);
}
