<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;

class IntegralMallStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'date',
                'value' => '日期',
            ],
            [
                'key' => 'user_num',
                'value' => '兑换商品人数',
            ],
            [
                'key' => 'goods_num',
                'value' => '兑换商品数',
            ],
            [
                'key' => 'coupons_num',
                'value' => '兑换优惠券数',
            ],
            [
                'key' => 'goods_integral',
                'value' => '积分支出',
            ],
            [
                'key' => 'goods_price',
                'value' => '金额支出',
            ],
        ];
    }

    public function export($query = null)
    {
        $query = $this->query->groupBy('`date`')->orderBy('`date` desc');

        $fieldsKeyList = [];
        foreach ($this->fieldsList() as $item) {
            $fieldsKeyList[] = $item['key'];
        }
        $this->fieldsKeyList = $fieldsKeyList;

        $this->exportAction($query, ['is_array' => true]);

        return true;
    }

    public function getFileName()
    {
        return '积分商城';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            $item['user_num'] = intval($item['user_num']);
            $item['goods_num'] = intval($item['goods_num']);
            $item['coupons_num'] = intval($item['coupons_num']);
            $item['goods_integral'] = intval($item['goods_integral']);
            $item['goods_price'] = floatval($item['goods_price']);

            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }
}
