<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;

class IntegralStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'date',
                'value' => '日期',
            ],
            [
                'key' => 'in_integral',
                'value' => '积分收入',
            ],
            [
                'key' => 'out_integral',
                'value' => '积分支出',
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
        return '积分收支';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            $item['in_integral'] = intval($item['in_integral']);
            $item['out_integral'] = intval($item['out_integral']);

            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }
}
