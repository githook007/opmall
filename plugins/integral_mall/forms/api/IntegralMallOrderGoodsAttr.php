<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\api;


use app\forms\api\order\OrderGoodsAttr;
use app\plugins\integral_mall\models\IntegralMallGoodsAttr;

class IntegralMallOrderGoodsAttr extends OrderGoodsAttr
{
    public function getAttrExtra()
    {
        $iAttr = IntegralMallGoodsAttr::findOne(['goods_attr_id' => $this->id, 'is_delete' => 0]);
        return ['integral_num' => $iAttr->integral_num];
    }
}
