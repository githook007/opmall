<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/4
 * Time: 17:16
 */

namespace app\plugins\region\events;

use app\plugins\region\models\RegionUser;
use yii\base\Event;

class RegionEvent extends Event
{
    /** @var RegionUser $region */
    public $region;

    //原来的等级
    public $originLevel;
}
