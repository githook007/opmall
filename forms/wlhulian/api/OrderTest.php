<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 测试回调接口，主动触发回调
class OrderTest extends Base
{
    /** @var string 平台订单号 */
    public $orderNo;
    /** @var int 状态 1:待接单 2：取货 3：配送 4：完成 5：取消 6：配送异常 */
    public $status;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/order/test";
    }
}
