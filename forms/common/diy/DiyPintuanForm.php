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
use app\plugins\pintuan\forms\common\CommonGoods;
use app\plugins\pintuan\models\Goods;
use app\plugins\pintuan\models\PintuanGoods;
use app\plugins\pintuan\models\PintuanGoodsGroups;
use app\plugins\pintuan\Plugin;

class DiyPintuanForm extends Model
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
        // @czs 修复首页不展示
        return $this->getNewGoodsById($goodsIds);
    }

    public function getNewGoodsById($goodsIds)
    {
        // 有阶梯团的商品 才在前端展示
        $pintuanGoodsIds = PintuanGoods::find()->where(['>', 'pintuan_goods_id', 0])
            ->andWhere(['is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])
            ->groupBy('pintuan_goods_id')
            ->select('pintuan_goods_id');

        $goodsIds = PintuanGoods::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'id' => $pintuanGoodsIds,
            'goods_id' => $goodsIds
        ])
            ->andWhere([
                'or',
                ['end_time' => '0000-00-00 00:00:00'],
                ['>', 'end_time', mysql_timestamp()]
            ])->select('goods_id');

        $list = Goods::find()->where([
            'id' => $goodsIds,
            'status' => 1,
            'is_delete' => 0,
        ])->with('goodsWarehouse')
            ->andWhere(CommonGoodsList::showAuthCondition())->all();

        return $this->getGoodsList($list);
    }

    // 插件优化后废弃
    public function getNewGoods($data, $goods)
    {
        // @czs
        $newArr = [];
        foreach ($data['list'] as $item) {
            foreach ($goods as $gItem) {
                try {
                    if ($item['id'] == $gItem['id']) {
                        $newArr[] = $gItem;
                        break;
                    }
                } catch (\Exception $exception) {

                }
            }
        }

        $data['list'] = $newArr;
        return $data;
    }

    /**
     * @param $arr
     * @param Goods $item
     * @return array
     */
    public function extraGoods($arr, $item)
    {
        $goodsList = $item->getGoodsGroups($item);
        $arr['people_num'] = 0;
        $arr['pintuan_price'] = 0;
        /*** @var Goods $goods */
        foreach ($goodsList as $key => $goods) {
            if (!$key) {
                $arr['people_num'] = $goods->oneGroups->people_num;
                $arr['pintuan_price'] = $goods->attr[0]->price;
                $arr['price_content'] = '￥' . $goods->attr[0]->price;
            }
        }
        return $arr;
    }
}
