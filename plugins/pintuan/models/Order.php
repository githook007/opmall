<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\models;


use app\models\OrderDetail;

/**
 * @property OrderDetail[] $orderDetail
 * @property PintuanOrderRelation $orderRelation
 */

class Order extends \app\models\Order
{
    public function getOrderRelation()
    {
        return $this->hasOne(PintuanOrderRelation::className(), ['order_id' => 'id']);
    }

    public function getOrderDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }
}
