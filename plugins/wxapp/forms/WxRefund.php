<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/18
 * Time: 16:05
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\wxapp\forms;


use app\bootstrap\payment\PaymentException;
use app\forms\common\refund\BaseRefund;
use app\models\PaymentRefund;
use app\plugins\wxapp\Plugin;
use luweiss\Wechat\WechatException;

class WxRefund extends BaseRefund
{
    /**
     * @param PaymentRefund $paymentRefund
     * @param \app\models\PaymentOrderUnion $paymentOrderUnion
     * @return bool|mixed
     * @throws PaymentException
     */
    public function refund($paymentRefund, $paymentOrderUnion)
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            $wechatPay = (new Plugin())->getWechatPay(Enum::WECHAT_PAY_SERVICE);
            // 微信退款
            $wechatPay->refund([
                'out_trade_no' => $paymentRefund->out_trade_no,
                'out_refund_no' => $paymentRefund->order_no,
                'total_fee' => $paymentOrderUnion->amount * 100,
                'refund_fee' => $paymentRefund->amount * 100,
            ]);
            $this->save($paymentRefund);
            $t->commit();
            return true;
        } catch (WechatException $e) {
            $t->rollBack();
            throw new PaymentException($e->getMessage());
        } catch (\Exception $e) {
            $t->rollBack();
            \Yii::error($e);
            throw new PaymentException('请检查支付证书是否填写正确');
        }
    }

    /**
     * @param PaymentRefund $paymentRefund
     * @throws \Exception
     */
    private function save($paymentRefund)
    {
        $paymentRefund->is_pay = 1;
        $paymentRefund->pay_type = 1;
        if (!$paymentRefund->save()) {
            throw new \Exception($this->getErrorMsg($paymentRefund));
        }
    }
}
