<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 查看订单详情
class OrderDetail extends Base
{
    /** @var string 平台订单号 */
    public $orderNo;

    /** @var string 接入方订单号 */
    public $outOrderNo;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/order/query/detail";
    }
}
