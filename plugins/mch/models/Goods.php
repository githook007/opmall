<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\models;


/**
 * Class Goods
 * @package app\plugins\mch\models
 * @property MchGoods mchGoods
 * @property Mch mch
 */
class Goods extends \app\models\Goods
{
    public function getMch()
    {
        return $this->hasOne(Mch::className(), ['id' => 'mch_id']);
    }

    public function getMchGoods()
    {
        return $this->hasOne(MchGoods::className(), ['goods_id' => 'id']);
    }
}
