<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/23 16:32
 */


namespace app\plugins\integral_mall\handlers;


use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            OrderCanceledHandler::class,
            OrderRefundConfirmedHandler::class,
        ];
    }
}
