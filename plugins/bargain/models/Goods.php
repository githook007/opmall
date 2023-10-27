<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/15
 * Time: 14:19
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\models;

/**
 * Class Goods
 * @package app\plugins\bargain\models
 * @property BargainGoods $bargainGoods
 */
class Goods extends \app\models\Goods
{
    public function getBargainGoods()
    {
        return $this->hasOne(BargainGoods::className(), ['goods_id' => 'id']);
    }
}
