<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/2
 * Time: 16:22
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\composition\handlers;


use app\handlers\HandlerBase;
use app\models\Order;
use app\plugins\composition\forms\common\CommonGoods;

class OrderPayedHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_PAYED, function ($event) {
            // 添加订单支付完成事件
            if ($event->order->sign != 'composition') {
                return true;
            }
            // 套餐商品统计
            $res = CommonGoods::getCommon()->setGoodsPayment($event->order, 'add');
            \Yii::warning('套餐商品统计');
            return true;
        });
    }
}
