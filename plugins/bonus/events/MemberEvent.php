<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/30
 * Time: 17:15
 */

namespace app\plugins\bonus\events;

use app\plugins\bonus\models\BonusCaptain;
use yii\base\Event;

class MemberEvent extends Event
{
    /** @var BonusCaptain $captain */
    public $captain;
}