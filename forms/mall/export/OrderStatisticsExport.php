<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;

class OrderStatisticsExport extends BaseExport
{

    public $name;

    public function fieldsList()
    {
        return [
            [
                'key' => 'time',
                'value' => '日期',
            ],
            [
                'key' => 'user_num',
                'value' => '付款人数',
            ],
            [
                'key' => 'order_num',
                'value' => '付款订单数',
            ],
            [
                'key' => 'total_pay_price',
                'value' => '付款金额',
            ],
            [
                'key' => 'goods_num',
                'value' => '付款件数',
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
        $ex_name = !empty($this->name) ? $this->name . '-' : '';
        $fileName = $ex_name . '销售统计';

        return $fileName;
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        $number = 1;
        foreach ($list as $key => $item) {
            $arr['number'] = $number++;
            $item['user_num'] = intval($item['user_num']);
            $item['order_num'] = intval($item['order_num']);
            $item['total_pay_price'] = floatval($item['total_pay_price']);
            $item['goods_num'] = intval($item['goods_num']);
            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }
}
