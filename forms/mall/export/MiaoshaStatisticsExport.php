<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;

class MiaoshaStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'miaosha_time',
                'value' => '秒杀时间',
            ],
            [
                'key' => 'name',
                'value' => '商品名称',
            ],
            [
                'key' => 'user_num',
                'value' => '支付人数',
            ],
            [
                'key' => 'goods_num',
                'value' => '支付件数',
            ],
            [
                'key' => 'pay_price',
                'value' => '支付金额',
            ],
            [
                'key' => 'status',
                'value' => '状态',
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

        $this->exportAction($query, ['is_array' => true]);

        return true;
    }

    public function getFileName()
    {
        return '整点秒杀统计';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            $item['user_num'] = intval($item['user_num']);
            $item['goods_num'] = intval($item['goods_num']);
            $item['pay_price'] = floatval($item['pay_price']);

            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }
}
