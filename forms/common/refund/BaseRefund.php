<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/18
 * Time: 11:54
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\refund;


use app\models\Model;
use app\models\PaymentOrderUnion;
use app\models\PaymentRefund;

abstract class BaseRefund extends Model
{
    /**
     * @param PaymentRefund $paymentRefund
     * @param PaymentOrderUnion $paymentOrderUnion
     * @return mixed
     */
    abstract public function refund($paymentRefund, $paymentOrderUnion);
}
