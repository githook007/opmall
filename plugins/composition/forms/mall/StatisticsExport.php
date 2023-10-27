<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/4
 * Time: 14:36
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\composition\forms\mall;


use app\bootstrap\CsvExport;
use app\forms\mall\export\BaseExport;
use app\plugins\composition\models\CompositionGoods;

class StatisticsExport extends BaseExport
{
    public function fieldsList()
    {
        return [
            [
                'key' => 'name',
                'value' => '商品名称'
            ],
            [
                'key' => 'payment_people',
                'value' => '支付人数'
            ],
            [
                'key' => 'payment_num',
                'value' => '支付件数'
            ],
            [
                'key' => 'payment_amount',
                'value' => '支付金额'
            ],
        ];
    }

    public function export($query = null)
    {
        $query = $this->query;
        
        $fieldsKeyList = [];
        foreach ($this->fieldsList() as $item) {
            $fieldsKeyList[] = $item['key'];
        }
        $this->fieldsKeyList = $fieldsKeyList;

        $this->exportAction($query);

        return true;
    }

    public function getFileName()
    {
        return '套餐组合统计';
    }

    public function transform($list)
    {
        /* @var CompositionGoods[] $list */
        $newList = [];
        foreach ($list as $key => $compositionGoods) {
            $newItem = [
                'name' => $compositionGoods->goods->goodsWarehouse->name,
                'payment_people' => $compositionGoods->payment_people,
                'payment_num' => $compositionGoods->payment_num,
                'payment_amount' => $compositionGoods->payment_amount,
            ];

            $newList[] = $newItem;
        }
        $this->dataList = $newList;
    }
}
