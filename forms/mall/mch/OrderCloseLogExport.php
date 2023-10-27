<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\mch;

use app\bootstrap\CsvExport;
use app\forms\mall\export\BaseExport;

class OrderCloseLogExport extends BaseExport
{
    public function fieldsList()
    {
        return [
            [
                'key' => 'platform',
                'value' => '所属平台',
            ],
            [
                'key' => 'total_pay_price',
                'value' => '订单金额',
            ],
            [
                'key' => 'order_no',
                'value' => '订单号',
            ],
            [
                'key' => 'goods_name',
                'value' => '商品信息',
            ],
            [
                'key' => 'status',
                'value' => '结算状态',
            ],
            [
                'key' => 'created_at',
                'value' => '订单日期',
            ],
        ];
    }

    public function export($query = null)
    {
        $query = $this->query;
        $query->with('user.userInfo', 'mchOrder', 'detail.goods')->orderBy('created_at');
        
        $this->exportAction($query);

        return true;
    }

    public function getFileName()
    {
        return '结算记录';
    }

    protected function transform($list)
    {
        $newList = [];
        $number = 1;
        foreach ($list as $item) {
            $arr = [];
            $arr['number'] = $number++;
            $arr['platform'] = $this->getPlatform($item->user);
            $arr['total_pay_price'] = (float)$item->total_pay_price;
            $arr['created_at'] = $this->getDateTime($item->created_at);
            $arr['order_no'] = $item->order_no;
            switch ($item->mchOrder->is_transfer) {
                case 0:
                    $arr['status'] = '未结算';
                    break;
                case 1:
                    $arr['status'] = '已结算';
                    break;
                default:
                    $arr['status'] = '未知';
                    break;
            }
            $goodsName = '';
            foreach ($item->detail as $dItem) {
                $goodsName .= $dItem->goods->name . '|';
            }
            $arr['goods_name'] = substr($goodsName, 0, strlen($goodsName) - 1);
            $newList[] = $arr;
        }

        $this->dataList = $newList;
    }
}
