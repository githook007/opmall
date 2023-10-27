<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 充值订单查询
class QueryRechargeStatus extends Base
{
    /** @var string 充值单号 */
    public $rechargeOrdNo;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/wallet/queryRechargeStatus";
    }
}
