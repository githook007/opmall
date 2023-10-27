<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/11/22
 * Time: 16:33
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\gift\handlers;


use app\handlers\orderHandler\OrderCreatedHandlerClass;

class OrderCreatedHandler extends OrderCreatedHandlerClass
{

    protected function setShareMoney()
    {
        return $this;
    }


}
