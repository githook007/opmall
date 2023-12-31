<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/20
 * Time: 4:03 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\handlers;

use app\events\OrderEvent;
use app\handlers\HandlerBase;
use app\models\Order;
use app\plugins\minishop\forms\OrderForm;

class OrderConfirmedHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_CONFIRMED, function ($event) {
            try {
                \Yii::warning('---自定义版交易组件 确认收货事件---');
                /* @var OrderEvent $event */
                $form = new OrderForm();
                $form->order = $event->order;
                $form->type = 'confirm';
                $form->execute();
            } catch (\Exception $exception) {
                \Yii::warning($exception);
            }
            return true;
        });
    }
}
