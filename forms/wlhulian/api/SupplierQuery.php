<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 查询平台支持运力列表
class SupplierQuery extends Base
{
    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/supplier/query";
    }

    public function supportStoreId(): bool
    {
        return false;
    }
}
