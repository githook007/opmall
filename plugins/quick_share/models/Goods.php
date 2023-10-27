<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\quick_share\models;


class Goods extends \app\models\Goods
{
    public function getQuickShareGoods()
    {
        return $this->hasOne(QuickShareGoods::className(), ['goods_id' => 'id'])
            ->andWhere(['is_delete' => 0]);
    }
}