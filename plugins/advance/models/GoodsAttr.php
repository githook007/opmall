<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/5/11
 * Time: 11:30
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\advance\models;

/**
 * @property AdvanceGoodsAttr $attr
 */
class GoodsAttr extends \app\models\GoodsAttr
{
    public function getAttr()
    {
        return $this->hasOne(AdvanceGoodsAttr::className(), ['goods_attr_id' => 'id'])->where(['is_delete' => 0]);
    }
}
