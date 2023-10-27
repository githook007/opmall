<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/2/28
 * Time: 9:14
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\composition\handlers;


use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            GoodsDestroyHandler::class,
            OrderPayedHandler::class,
            OrderCanceledHandler::class,
        ];
    }
}
