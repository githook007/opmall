<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\forms\api\goods\ApiGoods;
use app\forms\common\goods\CommonGoodsList;
use app\forms\common\goods\CommonGoodsMember;
use app\models\Model;
use app\plugins\booking\models\Goods;

class DiyBookingForm extends Model
{
    use TraitGoods;

    public function getGoodsIds($data)
    {
        $goodsIds = [];
        foreach ($data['list'] as $item) {
            $goodsIds[] = $item['id'];
        }

        return $goodsIds;
    }

    public function getGoodsById($goodsIds)
    {
        if (!$goodsIds) {
            return [];
        }

        $list = Goods::find()->where([
            'id' => $goodsIds,
            'status' => 1,
            'is_delete' => 0
        ])->with('goodsWarehouse')
            ->andWhere(CommonGoodsList::showAuthCondition())
            ->all();

        return $this->getGoodsList($list);
    }

    public function getNewGoods($data, $goods)
    {
        $newArr = [];
        foreach ($data['list'] as $item) {
            foreach ($goods as $gItem) {
                if ($item['id'] == $gItem['id']) {
                    $newArr[] = $gItem;
                    break;
                }
            }
        }

        $data['list'] = $newArr;

        return $data;
    }
}
