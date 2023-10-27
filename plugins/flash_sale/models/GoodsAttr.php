<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/4/30
 * Time: 16:25
 */

namespace app\plugins\flash_sale\models;

class GoodsAttr extends \app\models\GoodsAttr
{
    public function getAttr()
    {
        return $this->hasOne(FlashSaleGoodsAttr::className(), ['goods_attr_id' => 'id'])->where(['is_delete' => 0]);
    }
}
