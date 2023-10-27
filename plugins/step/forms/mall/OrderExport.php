<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\step\forms\mall;

class OrderExport extends \app\forms\mall\export\OrderExport
{
    public $send_type;

    public function getFileName()
    {
        $name = $this->send_type == 1 ? '步数宝-自提订单' : '步数宝-订单列表';
        $fileName = $name;

        return $fileName;
    }
}
