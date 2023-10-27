<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\handlers;

use app\models\Goods;
use app\models\GoodsHotSearch;

class GoodsStatusHandler extends HandlerBase
{
    /**
     * 事件处理
     */
    public function register()
    {
        \Yii::$app->on(Goods::EVENT_STATUS, function ($event) {
            if (empty($id = $event->id)) {
                \Yii::error('数据处理错误');
            }
            // 删除热搜
            if (intval($after = $event->status_after) === 0 && !empty($ids = is_array($id) ? $id : [$id])) {
                //真删
                GoodsHotSearch::deleteAll([
                    'goods_id' => $ids,
                    'is_delete' => 0,
                ]);
            }
        });
    }
}
