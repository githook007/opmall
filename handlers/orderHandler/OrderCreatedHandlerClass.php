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

class OrderCreatedHandlerClass extends BaseOrderCreatedHandler
{
    public function handle()
    {
        $this->user = $this->event->order->user;

        $this->setAutoCancel()->setShareUser()->setShareMoney()->receiptPrint('order')->deleteCartGoods();
    }
}
