<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/24
 * Time: 2:13 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\erp\handlers;

use app\events\OrderRefundEvent;
use app\handlers\HandlerBase;
use app\models\OrderRefund;
use app\plugins\erp\forms\common\data\RefundForm;

class OrderUpdateRefundHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(OrderRefund::EVENT_UPDATE_REFUND, function ($event) {
            try {
                \Yii::warning('---erp 更新售后事件---');
                $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
                if (!in_array('erp', $permission)) {
                    \Yii::warning("无权限");
                    return;
                }
                /** @var OrderRefundEvent $event */
                \Yii::$app->setMchId($event->order_refund->mch_id);
                $form = new RefundForm();
                $form->refund = $event->order_refund;
                $form->upload();
            } catch (\Exception $exception) {
                \Yii::error($exception);
            }
        });
    }
}
