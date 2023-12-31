<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/18
 * Time: 16:05
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\teller\forms;


use app\forms\common\refund\BaseRefund;
use app\models\PaymentRefund;
use app\plugins\teller\Plugin;

class PosRefund extends BaseRefund
{
    /**
     * POS机退款
     * @param PaymentRefund $paymentRefund
     * @param \app\models\PaymentOrderUnion $paymentOrderUnion
     * @return bool|mixed
     * @throws PaymentException
     */
    public function refund($paymentRefund, $paymentOrderUnion)
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            $this->save($paymentRefund);
            $t->commit();
            return true;
        } catch (\Exception $e) {
            $t->rollBack();
        }
    }

    /**
     * @param PaymentRefund $paymentRefund
     * @throws \Exception
     */
    private function save($paymentRefund)
    {
        $paymentRefund->is_pay = 1;
        $paymentRefund->pay_type = 10;
        if (!$paymentRefund->save()) {
            throw new \Exception($this->getErrorMsg($paymentRefund));
        }
    }
}
