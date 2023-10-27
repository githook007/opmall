<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\forms\mall;


use app\forms\mall\order\BaseOrderForm;

class OrderForm extends BaseOrderForm
{
    protected function export($query)
    {
        $exp = new OrderExport();
        $exp->fieldsKeyList = $this->fields;
        $exp->send_type = $this->send_type;
        $exp->export($query);
    }

    protected function getFieldsList()
    {
        return (new OrderExport())->fieldsList();
    }
}
