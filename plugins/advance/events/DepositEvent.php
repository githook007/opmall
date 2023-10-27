<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/30
 * Time: 11:40
 */

namespace app\plugins\advance\events;

use app\plugins\advance\models\AdvanceOrder;
use yii\base\Event;

class DepositEvent extends Event
{
    /** @var AdvanceOrder $advanceOrder */
    public $advanceOrder;
}