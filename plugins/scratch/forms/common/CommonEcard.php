<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/30
 * Time: 14:06
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\scratch\forms\common;


use app\forms\common\ecard\CheckGoods;
use app\models\EcardOptions;
use app\models\EcardOrder;
use app\models\Goods;
use app\plugins\scratch\models\ScratchLog;

class CommonEcard extends \app\forms\common\ecard\CommonEcard
{
    /**
     * @param ScratchLog $scratchLog
     * @throws \Exception
     * @return bool
     * 中奖时，占用卡密数据
     */
    public function setEcardScratch($scratchLog)
    {
        /* @var Goods $goods */
        $goods = Goods::find()->with(['goodsWarehouse'])->where(['id' => $scratchLog->goods_id])->one();
        if ($goods->goodsWarehouse->type !== 'ecard') {
            \Yii::warning('不是卡密商品');
            return false;
        }
        $ecard = $this->getEcard($goods->goodsWarehouse->ecard_id);
        /* @var EcardOptions[] $list */
        $list = $this->getEcardOptionsByOccupy($ecard, 1);
        if (count($list) != 1) {
            throw new \Exception('卡密数据库存不足');
        }
        $data = [];
        $model = new EcardOrder();
        foreach ($list as $item) {
            $data[] = [
                'id' => null,
                'mall_id' => \Yii::$app->mall->id,
                'ecard_id' => $item->ecard_id,
                'value' => $item->value,
                'order_id' => 0,
                'order_detail_id' => 0,
                'is_delete' => 0,
                'token' => $item->token,
                'ecard_options_id' => $item->id,
                'user_id' => $scratchLog->user_id,
                'order_token' => $scratchLog->token,
            ];
        }
        \Yii::$app->db->createCommand()->batchInsert(
            EcardOrder::tableName(),
            array_keys($model->attributes),
            $data
        )->execute();
        EcardOptions::updateAll(['is_sales' => 1], ['id' => array_column($list, 'id')]);
        $this->updateStock($ecard);
        $this->log(new CheckGoods([
            'ecard' => $ecard,
            'status' => CheckGoods::STATUS_SALES,
            'sign' => 'scratch',
            'number' => 1,
            'goods_id' => $goods->id,
        ]));
    }
}
