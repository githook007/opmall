<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\gift\forms\common;


use app\forms\common\goods\CommonGoodsList;
use app\models\Goods;
use app\models\GoodsWarehouse;
use app\models\Model;
use app\plugins\gift\Plugin;

class CommonGoods extends Model
{
    public static function getCommon()
    {
        return new self();
    }

    public function getDiyGoods($array)
    {
        $goodsWarehouseId = null;
        if (isset($array['keyword']) && $array['keyword']) {
            $goodsWarehouseId = GoodsWarehouse::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
                ->keyword($array['keyword'], ['like', 'name', $array['keyword']])
                ->select('id');
        }
        $signName = (new Plugin())->getName();

        $goodsList = Goods::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'sign' => $signName
        ])
            ->keyword($goodsWarehouseId, ['goods_warehouse_id' => $goodsWarehouseId])
            ->orderBy('created_at DESC')
            ->page($pagination)
            ->all();

        $common = new CommonGoodsList();
        $newList = [];
        foreach ($goodsList as $goods) {
            $newItem = $common->getDiyBack($goods);
            $newList[] = $newItem;
        }
        return [
            'list' => $newList,
            'pagination' => $pagination
        ];
    }

}