<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/12
 * Time: 10:58
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\teller\handlers;

use app\handlers\orderHandler\OrderCanceledHandlerClass;
use app\models\Order;
use app\models\PaymentOrder;
use app\models\PaymentOrderUnion;
use app\plugins\teller\forms\OrderQueryForm;

class TellerOrderCanceledHandler extends OrderCanceledHandlerClass
{
    public function handle()
    {
        $this->user = $this->event->order->user;
        $this->cancel();
    }
}
