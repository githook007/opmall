<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/13
 * Time: 18:18
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\events;


use app\plugins\bargain\models\BargainGoods;
use yii\base\Event;

/**
 * @property BargainGoods $bargainGoods
 */
class BargainGoodsEvent extends Event
{
    public $bargainGoods;
}
