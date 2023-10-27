<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: zbj
 */

namespace app\plugins\advance\models;


class Order extends \app\models\Order
{
    public function getAdvanceOrder()
    {
        return $this->hasOne(AdvanceOrder::className(), ['order_id' => 'id']);
    }
}
