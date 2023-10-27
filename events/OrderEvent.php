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

class OrderEvent extends Event
{
    /** @var Order */
    public $order;

    public $cartIds = [];

    public $pluginData;

    /**
     * @var integer $action_type
     * 订单取消状态 3--用户取消 4--超时未支付 5:商家取消;10:其他原因取消
     */
    public $action_type = 5;
}
