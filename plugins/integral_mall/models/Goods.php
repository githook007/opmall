<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\models;


/**
 * Class Goods
 * @package app\plugins\integral_mall\models
 * @property IntegralMallGoods $integralMallGoods
 */
class Goods extends \app\models\Goods
{
    public function getIntegralMallGoods()
    {
        return $this->hasOne(IntegralMallGoods::className(), ['goods_id' => 'id']);
    }
}
