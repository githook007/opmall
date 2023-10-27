<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/19
 * Time: 11:14
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\transfer;


use app\models\Model;
use app\models\PaymentTransfer;
use app\models\User;

abstract class BaseTransfer extends Model
{
    /**
     * @param PaymentTransfer $paymentTransfer
     * @param User $user
     * @return mixed
     */
    abstract public function transfer($paymentTransfer, $user);
}
