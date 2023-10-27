<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/22
 * Time: 10:02 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\handlers;

use app\events\PaymentOrderEvent;
use app\forms\common\order\weixin\OrderForm;
use app\models\PaymentOrderUnion;

class OrderTradeManageHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(PaymentOrderUnion::EVENT_TRADE_COMPLETE, function ($event) {
            /** @var PaymentOrderEvent $event */
            \Yii::warning('---支付后的记录---');
            try {
                if($event->paymentOrderUnion->platform == 'wxapp') {
                    OrderForm::getCommon(['paymentOrderUnion' => $event->paymentOrderUnion])->saveData();
                }
            }catch (\Exception $e){
                \Yii::error($e);
            }
        });
    }
}
