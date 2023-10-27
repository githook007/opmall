<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\order;


use app\forms\common\mch\MchSettingForm;
use app\models\BaseQuery\BaseActiveQuery;

class OrderForm extends BaseOrderForm
{
    protected function extraConfirmWhere()
    {
        if (\Yii::$app->user->identity->mch_id > 0) {
            $mchSettingForm = new MchSettingForm();
            $mchSetting = $mchSettingForm->search();

            if (!$mchSetting['is_confirm_order']) {
                throw new \Exception('商户无权限确认收货');
            }
        }
    }

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
        ]);
    }

    public function getNumList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $numList = [];
        for ($i = -1; $i <= 9; $i++) {
            if($i == 6){
                continue;
            }
            $this->status = $i;
            $numList[$i] = (int)$this->getAllQuery()->count();
        }
        return $this->success([
            'numList' => $numList
        ]);
    }
}
