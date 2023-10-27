<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\handler;


use app\handlers\orderHandler\OrderCreatedHandlerClass;

class OrderCreatedHandler extends OrderCreatedHandlerClass
{
    public function handle()
    {
        $this->user = $this->event->order->user;

        $this->setAutoCancel()->setShareUser()->setShareMoney();
    }
}