<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/5
 * Time: 14:53
 */

namespace app\plugins\advance\events;

use app\plugins\advance\models\AdvanceGoods;
use yii\base\Event;

class GoodsEvent extends Event
{
    /** @var AdvanceGoods $advanceGoods */
    public $advanceGoods;
}