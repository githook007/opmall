<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/15
 * Time: 14:44
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\events;


use app\plugins\bargain\models\BargainOrder;
use app\plugins\bargain\models\BargainUserOrder;
use yii\base\Event;

/**
 * @property BargainUserOrder[] $bargainUserOrderAll
 * @property BargainOrder $bargainOrder
 */
class BargainUserOrderEvent extends Event
{
    public $bargainUserOrderAll;
    public $bargainOrder;
}
