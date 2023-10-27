<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\forms\api\goods\ApiGoods;
use app\forms\common\goods\CommonGoodsList;
use app\models\Model;
use app\plugins\miaosha\models\Goods;
use app\plugins\miaosha\models\MiaoshaActivitys;
use app\plugins\miaosha\models\MiaoshaGoods;
use app\plugins\miaosha\Plugin;

class DiyMiaoshaForm extends Model
{
    use TraitGoods;

    public function getGoodsIds($data)
    {
        $goodsIds = [];
        if ($data['addGoodsType'] == 1) {
            foreach ($data['list'] as $item) {
                $goodsIds[] = $item['id'];
            }
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

    private function getNewGoodsById($goodsIds)
    {
        $activityIds = MiaoshaActivitys::find()
            ->where(['status' => 1, 'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
            ->select('id');
        $goodsIds = MiaoshaGoods::find()->where([
            'goods_id' => $goodsIds,
            'mall_id' => \Yii::$app->mall->id,
            'activity_id' => $activityIds
        ])->select('goods_id');
        $list = Goods::find()->where([
            'id' => $goodsIds,
            'status' => 1,
            'is_delete' => 0
        ])->with('goodsWarehouse', 'miaoshaGoods')
            ->andWhere(CommonGoodsList::showAuthCondition())->all();

        return $this->getGoodsList($list);
    }

    public function getNewGoods($data, $goods)
    {
        if ($data['addGoodsType'] == 0) {
            $plugin = \Yii::$app->plugin->getPlugin('miaosha');
            $newArr = $plugin->getHomePage('api', $data['goodsLength']);
            $data['mData'] = $newArr;
        } else {
            $newArr = [];
            foreach ($data['list'] as &$item) {
                foreach ($goods as $gItem) {
                    if ($item['id'] == $gItem['id']) {
                        $newArr[] = $gItem;
                        break;
                    }
                }
            }
            $data['list'] = $newArr;
        }

        return $data;
    }

    /**
     * @param $arr
     * @param Goods $goods
     * @return array
     */
    public function extraGoods($arr, $goods)
    {
        $arr['start_time'] = $goods->miaoshaGoods->open_date . ' ' . $goods->miaoshaGoods->open_time . ':00:00';
        $arr['end_time'] = $goods->miaoshaGoods->open_date . ' ' . $goods->miaoshaGoods->open_time . ':59:59';
        return $arr;
    }
}
