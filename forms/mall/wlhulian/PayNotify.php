<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/16 11:21
 */

namespace app\forms\mall\wlhulian;

use app\bootstrap\payment\PaymentNotify;
use app\bootstrap\payment\PaymentOrder;
use app\models\WlhulianWalletLog;

class PayNotify extends PaymentNotify
{

    /**
     * @param PaymentOrder $paymentOrder
     * @return mixed
     */
    public function notify($paymentOrder)
    {
        $wlHulianModel = \Yii::$app->mall->wlHulian;
        if(!$wlHulianModel){
            return true;
        }

        $paymentOrderModel = \app\models\PaymentOrder::findOne(['order_no' => $paymentOrder->orderNo]);
        if(!$paymentOrderModel->is_pay){
            return true;
        }

        $wlHulianModel->balance += $paymentOrder->amount;
        if(!$wlHulianModel->save()){
            \Yii::error('聚合配送充值金额失败：'.$paymentOrder->orderNo);
            \Yii::error($wlHulianModel->getErrors());
            return true;
        }

        $model = new WlhulianWalletLog();
        $model->mall_id = \Yii::$app->mall->id;
        $model->order_no = $paymentOrder->orderNo;
        $model->user_id = $paymentOrderModel->paymentOrderUnion->user_id;
        $model->money = $paymentOrder->amount;
        $model->balance = $wlHulianModel->balance;
        $model->type = WlhulianWalletLog::ADD;
        if(!$model->save()){
            \Yii::error('聚合配送充值日志保存失败：'.$paymentOrder->orderNo);
            \Yii::error($model->getErrors());
            return true;
        }
        return true;
    }
}
