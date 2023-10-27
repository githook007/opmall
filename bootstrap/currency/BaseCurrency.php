<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/1/30
 * Time: 16:29
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\bootstrap\currency;


interface BaseCurrency
{
    // 收入
    public function add($price, $desc, $customDesc);

    // 支出
    public function sub($price, $desc, $customDesc);

    // 查询
    public function select();

    // 退款
    public function refund($price, $desc, $customDesc);
}
