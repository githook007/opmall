<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\bootstrap\response;


class ApiCode
{
    /**
     *  状态码：成功
     */
    const CODE_SUCCESS = 0;

    /**
     * 状态码：失败
     */
    const CODE_ERROR = 1;

    /**
     * 状态码：未登录
     */
    const CODE_NOT_LOGIN = -1;

    /**
     * 状态码：商城禁用
     */
    const CODE_STORE_DISABLED = -2;
    /**
     * 状态码：多商户未登录
     */
    const CODE_MCH_NOT_LOGIN = -3;
    /**
     * 状态码：未关注公众号
     */
    const CODE_WECHAT_SUBSCRIBE = 2;
}
