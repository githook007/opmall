<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/18
 * Time: 15:54
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\refund;


use app\bootstrap\payment\PaymentException;

class HuodaoRefund extends BaseRefund
{
    /**
     * @param \app\models\PaymentRefund $paymentRefund
     * @param \app\models\PaymentOrderUnion $paymentOrderUnion
     * @return bool|mixed
     * @throws PaymentException
     */
    public function refund($paymentRefund, $paymentOrderUnion)
    {
        $paymentRefund->is_pay = 1;
        $paymentRefund->pay_type = 2;
        if (!$paymentRefund->save()) {
            throw new PaymentException($this->getErrorMsg($paymentRefund));
        }
        return true;
    }
}
