<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/18
 * Time: 16:05
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\common;

use app\bootstrap\payment\PaymentException;
use app\forms\common\refund\BaseRefund;
use app\models\PaymentRefund;
use luweiss\Wechat\WechatException;

class AppRefund extends BaseRefund
{
    /**
     * @param PaymentRefund $paymentRefund
     * @param \app\models\PaymentOrderUnion $paymentOrderUnion
     * @return bool|mixed
     * @throws PaymentException
     */
    public function refund($paymentRefund, $paymentOrderUnion)
    {
        \Yii::warning("app开始退款了");
        try {
            $paymentOrder = null;
            foreach ($paymentOrderUnion->paymentOrder as $item){
                if($item->is_pay == 1){
                    $paymentOrder = $item;
                    break;
                }
            }
            if(!$paymentOrder){
                throw new PaymentException("订单状态错误");
            }
            $class = $this->payTypeClass($paymentOrder->pay_type);
            if ($class->refund($paymentRefund, $paymentOrderUnion)) {
                \Yii::warning($class);
                \Yii::warning("app结束退款了");
                return true;
            } else {
                throw new PaymentException("退款错误");
            }
        } catch (WechatException $e) {
            \Yii::warning("app退款error");
            throw new PaymentException($e->getMessage());
        } catch (\Exception $e) { \Yii::warning("app退款error");
            throw new PaymentException('请检查支付证书是否填写正确');
        }
    }

    private function payTypeClass($payType)
    {
        // 1=微信支付，2=货到付款，3=余额支付，4=支付宝支付，5=百度支付，6=头条支付, 7=微信H5支付，8支付宝H5支付,9.现金支付 10.pos机支付
        switch ($payType) {
            case 1:
                $class = 'app\\plugins\\app\\forms\\common\\refund\\WxRefund';
                break;
            case 3:
                $class = 'app\\forms\\common\\refund\\BalanceRefund';
                break;
            case 4:
                $class = 'app\\plugins\\aliapp\\forms\\AlipayRefund';
                break;
            case 5:
                $class = 'app\\plugins\\bdapp\\forms\\BdRefund';
                break;
            case 6:
                $class = 'app\\plugins\\ttapp\\forms\\TtRefund';
                break;
            case 7:
                $class = 'app\\forms\\common\\refund\\WxH5Refund';
                break;
            case 8:
                $class = 'app\\forms\\common\\refund\\AliH5Refund';
                break;
            case 9:
                $class = 'app\\plugins\\teller\\forms\\CashRefund';
                break;
            case 10:
                $class = 'app\\plugins\\teller\\forms\\PosRefund';
                break;
            default:
                throw new PaymentException('无效的支付方式');
        }

        if (!class_exists($class)) {
            throw new PaymentException('未安装相关平台的插件或未知的客户端平台，平台标识`');
        }

        return new $class();
    }
}
