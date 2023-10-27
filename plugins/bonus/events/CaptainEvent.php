<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/4
 * Time: 17:16
 */

namespace app\plugins\bonus\events;

use app\plugins\bonus\models\BonusCaptain;
use yii\base\Event;

class CaptainEvent extends Event
{
    /** @var BonusCaptain $captain */
    public $captain;

    /**之前的队长**/
    public $parentId;
}