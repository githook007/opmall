<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/19
 * Time: 17:55
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\ecard\handlers;


use app\handlers\HandlerBase;
use app\models\Order;
use app\forms\common\ecard\CommonEcard;
use app\plugins\ecard\forms\Model;
use app\models\EcardOptions;
use app\models\EcardOrder;

class OrderCanceledHandle extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(Order::EVENT_CANCELED, function ($event) {
            \Yii::warning('处理电子卡密商品取消事件');
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                CommonEcard::getCommon()->refundEcard([
                    'type' => 'order',
                    'order' => $event->order
                ]);
                $transaction->commit();
            } catch (\Exception $exception) {
                $transaction->rollBack();
                \Yii::warning($exception);
            }
        });
    }
}
