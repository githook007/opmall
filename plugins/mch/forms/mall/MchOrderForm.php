<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\mall;

use app\forms\mall\order\OrderForm;
use yii\db\Query;

class MchOrderForm extends OrderForm
{
    public $flag;

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        /** @var Query $query */
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
