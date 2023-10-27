<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scratch\forms\mall;


class OrderExport extends \app\forms\mall\export\OrderExport
{
    public $send_type;

    public function getFileName()
    {
        $name = $this->send_type == 1 ? '刮刮卡-自提订单' : '刮刮卡-订单列表';
        $fileName = $name;

        return $fileName;
    }
}
