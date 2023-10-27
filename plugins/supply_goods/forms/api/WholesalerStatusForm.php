<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\api;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\supply_goods\models\SupplyGoodsWholesaler;

class WholesalerStatusForm extends Model
{
    public $status;
    public $remarks;
    public $shop_id;

    public function rules()
    {
        return [
            [['status', 'shop_id'], 'required'],
            [['shop_id'], 'integer'],
            [['status', 'remarks'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'status' => '状态',
            'shop_id' => '店铺id',
        ];
    }

    public function handleStatus()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            SupplyGoodsWholesaler::updateAll(['status' => $this->status, 'examine_remarks' => $this->remarks], ['mall_id' => $this->shop_id]);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
