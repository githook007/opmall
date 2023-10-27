<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\models;

class Goods extends \app\models\Goods
{
    public function getEgoods()
    {
        return $this->hasOne(ExchangeGoods::className(), ['goods_id' => 'id']);
    }

    public function getLibrary()
    {
        return $this->hasOne(ExchangeLibrary::className(), ['id' => 'library_id'])
            ->viaTable(ExchangeGoods::tableName(), ['goods_id' => 'id']);
    }
}
