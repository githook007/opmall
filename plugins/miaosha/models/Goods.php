<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\models;


/**
 * @property MiaoshaGoods $miaoshaGoods
 */
class Goods extends \app\models\Goods
{
    public function getMiaoshaGoods()
    {
        return $this->hasOne(MiaoshaGoods::className(), ['goods_id' => 'id'])
            ->andWhere(['is_delete' => 0]);
    }
}
