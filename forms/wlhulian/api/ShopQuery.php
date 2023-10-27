<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 查询发货店铺详情
class ShopQuery extends Base
{
    /** @var string 外部方门店id */
    public $outShopId;

    /** @var string 平台方门店id */
    public $shopId;

    public function getAttribute()
    {
        return '';
    }

    public function getMethodName()
    {
        return "/api/v1/shop/query";
    }
}
