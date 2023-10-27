<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\events;

use yii\base\Event;

class OrderSendEvent extends Event
{
    public $order;
}
