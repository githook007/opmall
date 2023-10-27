<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 创建子应用
class SubCreate extends Base
{
    /** @var string 应用名称 */
    public $name;

    /** @var string 手机号（需要唯一） */
    public $phone;

    /** @var string 回调地址（订单状态变更等回调状态通知） */
    public $callbackUrl;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/app/sub/create";
    }
}
