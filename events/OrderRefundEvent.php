<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\events;


use app\models\OrderRefund;
use yii\base\Event;

/**
 * @property OrderRefund $order_refund
 */
class OrderRefundEvent extends Event
{
    public $order_refund;
    public $advance_refund;
}
