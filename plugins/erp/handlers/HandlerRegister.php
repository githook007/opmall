<?php


namespace app\plugins\erp\handlers;

use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            OrderPayedHandler::class,
            OrderCanceledHandler::class,
            OrderSentHandler::class,
            OrderCreateRefundHandler::class,
            OrderUpdateRefundHandler::class,
        ];
    }
}
