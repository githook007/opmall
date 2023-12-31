<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\forms\mall;


use app\forms\mall\order\BaseOrderForm;
use app\models\BaseQuery\BaseActiveQuery;

class OrderForm extends BaseOrderForm
{
    public $orderModel = 'app\plugins\pintuan\models\Order';

    protected function handleExtraData($list)
    {
        foreach ($list as &$item) {
            if ($item['orderRelation']['pintuanOrder']) {
                if ($item['orderRelation']['pintuanOrder']['status'] == 1 ||
                    $item['orderRelation']['pintuanOrder']['status'] == 3) {
                    $item['is_send_show'] = 0;
                    $item['is_cancel_show'] = 0;
                    $item['is_clerk_show'] = 0;
                }
            }
        }

        return $list;
    }

    /**
     * @param BaseActiveQuery $query
     * @return BaseActiveQuery mixed
     */
    protected function getExtraWhere($query)
    {
        return $query->with('orderRelation.pintuanOrder');
    }

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
