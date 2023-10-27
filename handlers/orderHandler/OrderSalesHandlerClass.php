<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/12
 * Time: 10:58
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\handlers\orderHandler;

class OrderSalesHandlerClass extends BaseOrderSalesHandler
{
    public function handle()
    {
        $this->user = $this->event->order->user;

        $this->sales();
    }
}
