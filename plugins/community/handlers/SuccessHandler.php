<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/13
 * Time: 16:19
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\handlers;


use app\events\OrderEvent;
use app\forms\common\order\CommonOrder;
use app\handlers\HandlerBase;

class SuccessHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(HandlerRegister::COMMUNITY_SUCCESS, function ($event) {
            /** @var OrderEvent $event */
            \Yii::$app->setMchId($event->order->mch_id);
            $commonOrder = CommonOrder::getCommonOrder($event->order->sign);
            $handler = new SuccessHandlerClass();
            $handler->orderConfig = $commonOrder->getOrderConfig();
            $handler->event = $event;
            $handler->setMall()->handle();
        });
    }
}
