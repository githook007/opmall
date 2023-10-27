<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\order_send_template;


use app\forms\mall\order\BaseOrderForm;
use app\models\BaseQuery\BaseActiveQuery;

class OrderForm extends BaseOrderForm
{
    /**
     * @param BaseActiveQuery $query
     * @return mixed
     */
    protected function getExtraWhere($query)
    {
         return $query->andWhere([
            'or',
            [
                'o.sign' => 'scan_code_pay',
                'o.is_pay' => 1,
                'o.is_sale' => 1,
                'o.is_confirm' => 1
            ],
            ['!=', 'o.sign', 'scan_code_pay']
        ])->andWhere([
            'or',
            ['o.is_pay' => 1],
            ['o.pay_type' => 2]
        ])
            ->andWhere(['o.is_confirm' => 0, 'o.is_recycle' => 0])
            ->andWhere(['!=', 'o.cancel_status', 1]);
    }
}