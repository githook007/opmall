<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\events;

use yii\base\Event;

class GoodsStatusEvent extends Event
{
    public $id;
    public $status_after;
}
