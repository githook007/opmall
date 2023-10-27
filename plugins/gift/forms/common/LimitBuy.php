<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/12/17
 * Time: 4:56 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\gift\forms\common;

use app\plugins\gift\models\GiftSendOrder;
use app\plugins\gift\models\GiftSendOrderDetail;

class LimitBuy extends \app\forms\common\goods\LimitBuy
{
    protected function getOrderNum($time)
    {
        return GiftSendOrderDetail::find()->alias('od')
            ->leftJoin(['o' => GiftSendOrder::tableName()], 'od.send_order_id=o.id')
            ->where([
                'od.goods_id' => $this->goods->id,
                'od.is_delete' => 0,
                'o.user_id' => \Yii::$app->user->id,
                'o.is_delete' => 0,
            ])
            ->sum('od.num');
    }
}
