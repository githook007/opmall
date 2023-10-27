<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/24
 * Time: 2:13 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\handlers;


use app\events\OrderRefundEvent;
use app\handlers\HandlerBase;
use app\models\OrderRefund;
use app\plugins\minishop\forms\RefundForm;

class OrderUpdateRefundHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(OrderRefund::EVENT_UPDATE_REFUND, function ($event) {
            try {

                /** @var OrderRefundEvent $event */
                \Yii::warning('---自定义版交易组件 更新售后事件---');
                $form = new RefundForm();
                $form->refund = $event->order_refund;
                $form->type = 'updateNew';
                $form->execute();
            } catch (\Exception $exception) {
                \Yii::error($exception);
                throw $exception;
            }
        });
    }
}
