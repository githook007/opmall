<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: zbj
 */

namespace app\plugins\advance\forms\api;


use app\forms\api\order\OrderGoodsAttr;
use app\plugins\advance\models\AdvanceGoodsAttr;

class AdvanceOrderGoodsAttr extends OrderGoodsAttr
{
    public function getAttrExtra()
    {
        $iAttr = AdvanceGoodsAttr::findOne(['goods_attr_id' => $this->id, 'is_delete' => 0]);
        return [
            'deposit' => $iAttr->deposit,
            'swell_deposit' => $iAttr->swell_deposit
        ];
    }
}
