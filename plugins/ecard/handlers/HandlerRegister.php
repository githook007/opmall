<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/17
 * Time: 11:33
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\ecard\handlers;


use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    public function getHandlers()
    {
        return [
            OrderCanceledHandle::class
        ];
    }
}
