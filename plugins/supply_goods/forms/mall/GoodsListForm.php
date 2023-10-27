<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\export\CommonExport;
use app\plugins\supply_goods\models\SupplyGoodsItem;

class GoodsListForm extends \app\forms\mall\goods\GoodsListForm
{
    protected function setQuery($query)
    {
        $goodsIds = SupplyGoodsItem::find()
            ->where([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
            ])
            ->select('goods_warehouse_id');
        $query->andWhere(['g.goods_warehouse_id' => $goodsIds])->with('mallGoods');

        if ($this->flag == "EXPORT") {
            if ($this->choose_list && count($this->choose_list) > 0) {
                $query->andWhere(['g.id' => $this->choose_list]);
            }

            $queueId = CommonExport::handle([
                'export_class' => 'app\\forms\\mall\\export\\MallGoodsExport',
                'params' => [
                    'query' => $query,
                ]
            ]);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'queue_id' => $queueId
                ]
            ];
        }

        return $query;
    }
}
