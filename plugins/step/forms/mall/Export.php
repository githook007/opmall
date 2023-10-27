<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\step\forms\mall;

use app\forms\mall\export\OrderExport;

class Export extends OrderExport
{
    public function getFileName()
    {
        $name = '步数宝-订单列表';
        $fileName = $name . date('YmdHis');
        return $fileName;
    }
}
