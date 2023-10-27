<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/19
 * Time: 11:34
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\wxapp\forms;

use app\bootstrap\payment\PaymentException;
use app\forms\common\CommonUser;
use app\forms\common\platform\PlatformConfig;
use app\forms\common\transfer\BaseTransfer;
use app\jobs\WechatTransferJob;
use app\plugins\wxapp\Plugin;

class WechatTransfer extends BaseTransfer
{
    /**
     * @param \app\models\PaymentTransfer $paymentTransfer
     * @param \app\models\User $user
     * @return bool
     * @throws PaymentException
     */
    public function transfer($paymentTransfer, $user)
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            $plugin = new Plugin();
            $wechatPay = $plugin->getWechatPay();
            $openid = PlatformConfig::instance()->getPlatformOpenid($user);
            $wechatPay->transfers([
                'partner_trade_no' => $paymentTransfer->order_no,
                'openid' => current($openid),
                'amount' => $paymentTransfer->amount * 100,
                'desc' => $paymentTransfer->title ?: '转账',
                'name' => CommonUser::whoUser($user)
            ]);
            $paymentTransfer->is_pay = 1;
            if (!$paymentTransfer->save()) {
                throw new \Exception($this->getErrorMsg($paymentTransfer));
            }
            $t->commit();
            return true;
        } catch (\Exception $e) {
            $t->rollBack();
            if($e->getCode() == 100){
                \Yii::$app->queue3->delay(1)->push(new WechatTransferJob([
                    'mall' => \Yii::$app->mall,
                    'post' => \Yii::$app->request->post(),
                ]));
            }
            throw new PaymentException($e->getMessage());
        }
    }
}
