<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\events;

use app\models\Goods;
use yii\base\Event;

class GoodsEvent extends Event
{
    /** @var Goods */
    public $goods;
    public $diffAttrIds;

    public $isVipCardGoods;
}
