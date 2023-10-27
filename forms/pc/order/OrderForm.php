<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\order;

use app\bootstrap\response\ApiCode;
use app\forms\pc\goods\MallGoods;
use app\forms\common\order\CommonOrder;
use app\forms\common\order\CommonOrderList;
use app\models\Mall;
use app\models\Order;
use app\models\OrderDetailExpress;
use yii\helpers\ArrayHelper;

class OrderForm extends \app\forms\api\order\OrderForm
{
    public $date_start;
    public $data_end;
    public $limit = 10;

    public function rules()
    {
        return array_merge([
            [['date_start', 'data_end'], 'trim'],
        ], parent::rules());
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        // 售后订单列表
        if ($this->status == 5) {
            return $this->getRefundOrderList();
        }

        $form = new CommonOrderList();
        $form->user_id = \Yii::$app->user->id;
        $form->status = $this->status;
        $form->is_detail = 1;
//        $form->is_goods = 1;
        $form->is_comment = 1;
        $form->keyword = $this->keyword;
        if($this->date_start && $this->data_end && !is_numeric($this->date_start) && !is_numeric($this->data_end)) {
            $form->dateArr = [date("Y-m-d", strtotime($this->date_start)), date("Y-m-d", strtotime($this->data_end))];
        }
        $form->page = $this->page;
        $form->is_recycle = 0;
        $form->add_where = [
            'or',
            [
                'o.sign' => 'scan_code_pay',
                'o.is_pay' => 1,
                'o.is_sale' => 1,
                'o.is_confirm' => 1,
            ],
            ['!=', 'o.sign', 'scan_code_pay'],
        ];
        $list = $form->search();

        $newList = [];
        $order = new Order();
        $this->isComment = (new Mall())->getMallSettingOne('is_comment');
        /* @var Order[] $list */
        foreach ($list as $item) {
            $newItem = ArrayHelper::toArray($item);
            $newItem['comments'] = $item->comments ? ArrayHelper::toArray($item->comments) : [];
            $newItem['detail'] = $item->detail ? ArrayHelper::toArray($item->detail) : [];
            $newItem['cancel_data'] = $item->cancel_data ? \Yii::$app->serializer->decode($item->cancel_data) : [];
            $newItem['status_text'] = $order->orderStatusText($item);
            $priceList = [];
            foreach ($item->detail as $key => $orderDetail) {
                $goodsInfo = MallGoods::getGoodsData($orderDetail);
                $newItem['detail'][$key]['goods_info'] = $goodsInfo;
                $priceList[] = [
                    'label' => '小计',
                    'value' => $orderDetail['total_price'],
                ];
            }

            $newDetailExpress = [];
            /** @var OrderDetailExpress $detailExpress */
            foreach ($item->detailExpress as $detailExpress) {
                $newDeItem = ArrayHelper::toArray($detailExpress);
                $newDetailExpress[] = $newDeItem;
            }
            $newItem['detailExpress'] = $newDetailExpress;
            $newItem['action_status'] = $this->getActionStatus($item);
            $newItem['plugin_data'] = $item->getPluginData($item, $priceList);

            $newList[] = $newItem;
        }
        $orderInfoCount = CommonOrder::getCommonOrder("")->getOrderInfoCount();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $newList,
                'pagination' => $form->pagination,
                "orderCount" => [
                    "wait_pay_count" => $orderInfoCount[0],
                    "wait_deliver_count" => $orderInfoCount[1],
                    "wait_receive_count" => $orderInfoCount[2],
                    "wait_comment_count" => $orderInfoCount[3],
                    "wait_sale_count" => $orderInfoCount[4],
                    "total_count" => $form->pagination->total_count
                ]
            ],
        ];
    }
}
