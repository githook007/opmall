<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/4
 * Time: 17:16
 */

namespace app\plugins\stock\events;

use app\plugins\stock\models\StockUser;
use yii\base\Event;

class StockEvent extends Event
{
    /** @var StockUser $stock */
    public $stock;
}
