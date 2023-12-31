<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/10/12
 * Time: 16:41
 */

namespace app\plugins\vip_card\models;


use app\models\OrderDetail;

/**
 * @property OrderDetail[] $orderDetail

 */

class Order extends \app\models\Order
{
    public function getOrder()
    {
        return $this->hasOne(VipCardOrder::className(), ['order_id' => 'id']);
    }

    public function getOrderDetail()
    {
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }
}
