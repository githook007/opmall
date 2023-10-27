<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: zbj
 */

namespace app\plugins\advance\models;


use app\models\GoodsCatRelation;

/**
 * Class Goods
 * @package app\plugins\advance\models
 * @property AdvanceGoods $advanceGoods
 * @property GoodsCatRelation $cat
 */
class Goods extends \app\models\Goods
{
    public function getAdvanceGoods()
    {
        return $this->hasOne(AdvanceGoods::className(), ['goods_id' => 'id']);
    }

    public function getCat()
    {
        return $this->hasOne(GoodsCatRelation::className(), ['goods_warehouse_id' => 'goods_warehouse_id']);
    }
}
