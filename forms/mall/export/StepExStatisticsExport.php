<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;

class StepExStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'c_date',
                'value' => '日期',
            ],
            [
                'key' => 'step_num',
                'value' => '步数兑换总数',
            ],
            [
                'key' => 'user_num',
                'value' => '兑换人数',
            ],
            [
                'key' => 'goods_num',
                'value' => '兑换商品数',
            ],
            [
                'key' => 'goods_pay',
                'value' => '兑换商品支出（金额/活力币）',
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
        return '步数挑战兑换统计';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            $item['step_num'] = intval($item['step_num']);
            $item['user_num'] = intval($item['user_num']);
            $item['goods_num'] = intval($item['goods_num']);

            $arr=array_merge($arr,$item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }
}
