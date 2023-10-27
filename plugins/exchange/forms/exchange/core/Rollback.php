<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\exchange\core;

use app\models\Order;
use app\plugins\exchange\models\ExchangeCode;
use app\plugins\exchange\models\ExchangeRecordOrder;

class Rollback
{
    public function goods(Order $order)
    {
        $recordOrder = ExchangeRecordOrder::find()->where([
            'mall_id' => $order->mall_id,
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ])->one();
        /** @var ExchangeCode $codeModel */
        if (!$recordOrder || !($codeModel = $recordOrder->code)) {
            throw new \Exception('exchange 数据不存在');
        }
        $rewards = json_decode($codeModel->r_rewards, true);
        foreach ($rewards as $key => $reward) {
            if (
                $reward['is_send'] == 1
                && $reward['type'] === 'goods'
                && $reward['token'] === $recordOrder->token
            ) {
                $rewards[$key]['is_send'] = 0;
                $recordOrder->is_delete = 1;
                $codeModel->r_rewards = json_encode($rewards, JSON_UNESCAPED_UNICODE);
                $codeModel->status = 2;
                $codeModel->save();
                $recordOrder->save();
                break;
            }
        }
    }
}
