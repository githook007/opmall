<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/17
 * Time: 5:32 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\handlers;

use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{

    public function getHandlers()
    {
        return [
            OrderCanceledHandler::class,
            OrderPayedHandler::class,
            OrderSentHandler::class,
            OrderConfirmedHandler::class,
            OrderCreateRefundHandler::class,
            OrderUpdateRefundHandler::class,
        ];
    }
}
