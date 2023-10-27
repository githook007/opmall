<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/22
 * Time: 2:13 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\erp\handlers;

use app\events\OrderRefundEvent;
use app\handlers\HandlerBase;
use app\models\OrderRefund;
use app\plugins\erp\forms\common\data\RefundForm;

class OrderCreateRefundHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(OrderRefund::EVENT_CREATE_REFUND, function ($event) {
            try {
                /** @var OrderRefundEvent $event */
                \Yii::warning('---erp 创建售后事件---');
                $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
                if (!in_array('erp', $permission)) {
                    \Yii::warning("无权限");
                    return;
                }
                \Yii::$app->setMchId($event->order_refund->mch_id);
                $form = new RefundForm();
                $form->refund = $event->order_refund;
                $form->upload();
            } catch (\Exception $exception) {
                \Yii::error($exception);
//                throw $exception;
            }
        });
    }
}
