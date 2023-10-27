<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\mall;

use app\forms\mall\order\OrderForm;

class IntegralOrderForm extends OrderForm
{
    public $flag;

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $query = $this->where();

        if ($this->flag == "EXPORT") {
            $new_query = clone $query;
            $exp = new OrderExport();
            $exp->fieldsKeyList = $this->fields;
            $exp->send_type = $this->send_type;
            $exp->export($new_query);
            return false;
        }
    }
}
