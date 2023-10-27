<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\bargain\forms\mall;

use app\bootstrap\CsvExport;
use app\forms\mall\export\BaseExport;
use app\models\Order;

class OrderExport extends \app\forms\mall\export\OrderExport
{
    /**
     * 获取csv名称
     * @return string
     */
    public function getFileName()
    {
        if ($this->send_type == 1) {
            $name = '砍价-自提订单';
        } elseif ($this->send_type == 2) {
            $name = '砍价-同城配送';
        } else {
            $name = '砍价-订单列表';
        }
        $fileName = $name . date('YmdHis');

        return $fileName;
    }
}
