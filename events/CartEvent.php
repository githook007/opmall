<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/23 17:01
 */


namespace app\events;


use app\models\Order;
use yii\base\Event;

class CartEvent extends Event
{
    /** @var Order */
    public $cartIds;
}
