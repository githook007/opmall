<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\mall;


use app\forms\mall\goods\BaseGoodsList;
use app\models\BaseQuery\BaseActiveQuery;
use yii\helpers\ArrayHelper;

class GoodsListForm extends BaseGoodsList
{
    public $goodsModel = 'app\plugins\integral_mall\models\Goods';

    /**
     * @param BaseActiveQuery $query
     * @return mixed
     */
    protected function setQuery($query)
    {
        $query->andWhere([
            'g.sign' => \Yii::$app->plugin->getCurrentPlugin()->getName(),
        ])->with('integralMallGoods');

        return $query;
    }

    protected function handleGoodsData($goods)
    {
        $newItem = [];
        $newItem['integralMallGoods'] = isset($goods->integralMallGoods) ? ArrayHelper::toArray($goods->integralMallGoods) : [];

        return $newItem;
    }
}