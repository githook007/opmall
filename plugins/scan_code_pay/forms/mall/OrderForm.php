<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scan_code_pay\forms\mall;


use app\bootstrap\response\ApiCode;
use app\forms\mall\export\CommonExport;
use app\forms\mall\export\jobs\ExportJob;
use app\forms\mall\order\BaseOrderForm;

class OrderForm extends BaseOrderForm
{
    protected function getFieldsList()
    {
        return (new OrderExport())->fieldsList();
    }

    public function getModelClass()
    {
        return 'app\\plugins\\scan_code_pay\\forms\\mall\\OrderForm';
    }

    protected function export($query)
    {
        $queueId = CommonExport::handle([
            'export_class' => 'app\\plugins\\scan_code_pay\\forms\\mall\\OrderExport',
            'params' => [
                'fieldsKeyList' => $this->fields,
            ],
            'model_class' => $this->getModelClass(),
            'model_params' => ['sign' => 'scan_code_pay'],
            'function_name' => 'getAllQuery'
        ]);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'queue_id' => $queueId
            ]
        ];
    }

    /**
     * @param \app\models\BaseQuery\BaseActiveQuery $query
     * @return \app\models\BaseQuery\BaseActiveQuery|array
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
        ]);
    }
}