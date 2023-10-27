<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\mall;

use app\bootstrap\CsvExport;
use app\models\Order;

class OrderExport extends \app\forms\mall\export\OrderExport
{
    public $send_type;

    public function getFileName()
    {
        if ($this->send_type == 1) {
            $name = '积分商城-自提订单';
        } elseif ($this->send_type == 2) {
            $name = '积分商城-同城配送';
        } else {
            $name = '积分商城-订单列表';
        }
        $fileName = $name . date('YmdHis');

        return $fileName;
    }
}
