<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\step\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\export\CommonExport;
use app\forms\mall\export\jobs\ExportJob;
use app\forms\mall\order\OrderForm;

class StepOrderForm extends OrderForm
{
    protected function getFieldsList()
    {
        return (new OrderExport())->fieldsList();
    }

    public function getModelClass()
    {
        return 'app\\plugins\\step\\forms\\mall\\StepOrderForm';
    }

    protected function export($query)
    {
        $queueId = CommonExport::handle([
            'export_class' => 'app\\plugins\\step\\forms\\mall\\OrderExport',
            'params' => [
                'fieldsKeyList' => $this->fields,
                'send_type' => $this->send_type,
            ],
            'model_class' => $this->getModelClass(),
            'model_params' => ['sign' => 'step'],
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
}
