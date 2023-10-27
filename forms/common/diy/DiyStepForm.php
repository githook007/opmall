<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\forms\common\goods\CommonGoodsList;
use app\models\Model;
use app\plugins\step\models\Goods;

class DiyStepForm extends Model
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
        ])->with('goodsWarehouse', 'stepGoods')
            ->andWhere(CommonGoodsList::showAuthCondition())->all();

        return $this->getGoodsList($list);
    }

    public function getNewGoods($data, $goods)
    {
        $newGoodsList = [];
        foreach ($data['list'] as $item) {
            foreach ($goods as $gItem) {
                if ($item['id'] == $gItem['id']) {
                    $newGoodsList[] = $gItem;
                    break;
                }
            }
        }
        $data['list'] = $newGoodsList;
        return $data;
    }
}
