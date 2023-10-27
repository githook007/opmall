<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 查询运力审核状态
class ShopSupplierQuery extends Base
{
    /** @var string 外部方门店id */
    public $outShopId;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/shop/supplier/query";
    }
}
