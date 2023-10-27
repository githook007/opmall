<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 账户充值
class AccountRecharge extends Base
{
    /** @var string 充值金额（单位为分） */
    public $rechargePrice;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/wallet/accountRecharge";
    }
}
