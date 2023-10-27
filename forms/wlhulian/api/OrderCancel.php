<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 取消订单
class OrderCancel extends Base
{
    /** @var integer 取消类型 可选值： (1,"个人原因"), (2, "骑手配送不及时"), (3, "骑手无法配送"), (4, "骑手取货不及时"), (20, "其他"), */
    public $cancelType;

    /** @var string 取消原因 （当取消原因未其他时必填） */
    public $cancelMessage;

    /** @var string 接入方订单号 */
    public $outOrderNo;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/order/cancel";
    }
}
