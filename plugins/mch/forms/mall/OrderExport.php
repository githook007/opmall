<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\mall;


class OrderExport extends \app\forms\mall\export\OrderExport
{

    public function export($query = null)
    {
        $query = $this->query;
        $query->with(['user.userInfo', 'clerk', 'store', 'detail.goods.goodsWarehouse', 'refund', 'paymentOrder.paymentOrderUnion', 'detail.expressRelation.orderExpress', 'detailExpress', 'detail.refund'])
            ->with('mch.store', 'detail.goods.mch.store')
            ->orderBy('o.created_at DESC');


        $this->exportAction($query);

        return true;
    }

    public function fieldsList()
    {
        $exportFields = parent::fieldsList();
        foreach ($exportFields as $key =>  $item) {
            if ($item['key'] == 'city_name' || $item['key'] == 'city_mobile') {
                unset($exportFields[$key]);
            }
        }
        return array_values($exportFields);
    }

    public function getFileName()
    {
        if ($this->send_type == 1) {
            $name = '商户-自提订单';
        } elseif ($this->send_type == 2) {
            $name = '商户-同城配送';
        } else {
            $name = '商户-订单列表';
        }
        $fileName = $name;

        return $fileName;
    }
}
