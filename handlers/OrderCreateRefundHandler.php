<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/22
 * Time: 9:59 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\handlers;

use app\events\OrderRefundEvent;
use app\models\OrderRefund;

class OrderCreateRefundHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(OrderRefund::EVENT_CREATE_REFUND, function ($event) {
            /** @var OrderRefundEvent $event */
            \Yii::warning('---订单创建售后事件处理开始---');
        });
    }
}
