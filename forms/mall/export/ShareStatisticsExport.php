<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\bootstrap\CsvExport;
use app\models\CoreFile;

class ShareStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'id',
                'value' => 'ID',
            ],
            [
                'key' => 'nickname',
                'value' => '分销商',
            ],
            [
                'key' => 'first_children',
                'value' => '直接下级数量',
            ],
            [
                'key' => 'all_children',
                'value' => '总下级数量',
            ],
            [
                'key' => 'all_order',
                'value' => '分销商订单数',
            ],
            [
                'key' => 'all_money',
                'value' => '总佣金',
            ],
            [
                'key' => 'total_money',
                'value' => '累计佣金',
            ],
            [
                'key' => 'price',
                'value' => '已提现佣金',
            ],
        ];
    }

    public function export($query = null)
    {
        \Yii::warning('导出开始');
        try {

            $list = $this->query->with('user.userInfo')->asArray()->all();

            foreach ($list as $key => $value) {
                $list[$key]['nickname'] = $value['user']['nickname'];
                $list[$key]['avatar'] = $value['user']['userInfo']['avatar'];
                unset($list[$key]['user']);
            }

            $this->getFields();
            // 文件夹唯一标识
            $id = \Yii::$app->mall->id . '_' . $this->mch_id;
            // 唯一文件名称
            $fileName = sprintf('%s%s%s%s', $this->getFileName(), $id, time(), '.csv');

            $coreFile = new CoreFile();
            $coreFile->mall_id = \Yii::$app->mall->id;
            $coreFile->mch_id = $this->mch_id;
            $coreFile->file_name = $fileName;

            $this->transform($list);
            $dataList = $this->getDataList();
            (new CsvExport())->newAjaxExport($dataList, $this->fieldsNameList, $fileName, $id);

            $coreFile->status = 1;
            $coreFile->percent = 1;
            $res = $coreFile->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($coreFile));
            }

            \Yii::warning('导出结束');
        }catch(\Exception $exception) {
            \Yii::error('导出异常');
            \Yii::error($exception);

            $coreFile->status = 2;
            $coreFile->save();
        }
    }

    public function getFileName()
    {
        return '分销排行';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            $item['first_children'] = intval($item['first_children']);
            $item['all_children'] = intval($item['all_children']);
            $item['all_order'] = intval($item['all_order']);
            $item['all_money'] = floatval($item['all_money']);
            $item['total_money'] = floatval($item['total_money']);
            $item['price'] = floatval($item['price']);

            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }

    protected function getFields()
    {
        $arr = [];
        foreach ($this->fieldsList() as $key => $item) {
            $arr[$key] = $item['key'];
        }
        $this->fieldsKeyList = $arr;
        parent::getFields(); // TODO: Change the autogenerated stub
    }
}
