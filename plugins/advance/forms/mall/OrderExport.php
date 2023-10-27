<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/29
 * Time: 16:43
 */

namespace app\plugins\advance\forms\mall;


class OrderExport extends \app\forms\mall\export\OrderExport
{
    public function getFileName()
    {
        return '预售订单-订单列表';
    }
}
