<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\booking\forms\mall;


use app\forms\mall\order\BaseOrderForm;

class OrderForm extends BaseOrderForm
{
    protected function getFieldsList()
    {
        return (new OrderExport())->fieldsList();
    }
}