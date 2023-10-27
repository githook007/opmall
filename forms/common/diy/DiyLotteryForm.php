<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\models\Model;
use app\plugins\lottery\models\Goods;

class DiyLotteryForm extends Model
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
            'is_delete' => 0,
        ])->with('goodsWarehouse', 'lotteryGoods')->all();

        return $this->getGoodsList($list);
    }

    /**
     * @param $arr
     * @param Goods $goods
     * @return array
     */
    public function extraGoods($arr, $goods)
    {
        $arr['start_time'] = $goods->lotteryGoods->start_at;
        $arr['end_time'] = $goods->lotteryGoods->end_at;
        $arr['page_url'] = '/plugins/lottery/goods/goods?lottery_id=' . $goods->lotteryGoods->id;
        $arr['is_level'] = 0;
        $arr['price'] = '0.00';
        $arr['price_content'] = '免费';
        return $arr;
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
