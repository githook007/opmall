<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/10/11
 * Time: 10:09
 */

namespace app\events;

use app\models\GoodsCats;
use yii\base\Event;

class GoodsCatEvent extends Event
{
    /** @var GoodsCats */
    public $cats;

    public $catsList;

    public $isVipCardCats;
}
