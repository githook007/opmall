<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scan_code_pay\handlers;

use app\handlers\orderHandler\BaseOrderCanceledHandler;

class OrderCancelEventHandler extends BaseOrderCanceledHandler
{
    protected function action()
    {
        $this->integralResume()->couponResume()->refund()->cardResume()->shareResume()->updateGoodsInfo();
    }
}