<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\mch;

use app\bootstrap\response\ApiCode;
use app\forms\common\goods\CommonGoodsList;
use app\models\Model;

class GoodsForm extends Model
{
    public $keyword;
    public $page;
    public $id;
    public $mch_id;
    public $sort;
    public $sort_type;
    public $status;
    public $is_sold_out;
    public $mch_status = 2;
    public $cat_id;
    public $limit;

    public function rules()
    {
        return [
            [['mch_id'], 'required'],
            [['keyword'], 'string'],
            [['id', 'mch_id', 'sort', 'sort_type', 'is_sold_out', 'mch_status', 'cat_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 10],
            [['status'], 'safe'],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $form = new CommonGoodsList();
        $form->keyword = $this->keyword;
        $form->mch_id = $this->mch_id;
        $form->sort = $this->sort ?: 1;
        $form->cat_id = $this->cat_id;
        $form->sort_type = $this->sort_type == 0 ? $this->sort_type : 1;
        $form->page = $this->page;
        $form->limit = $this->limit;
        if ($this->status != 2) {
            $form->status = $this->status;
        }
        if ($this->sort == 4) {
            $form->is_sales = 1;
        }
        $form->sign = "mch";
        $form->is_sold_out = $this->is_sold_out ?: null;
        $form->mch_status = $this->mch_status;
        $form->relations = ['goodsWarehouse'];
        $list = $form->search();

        $newList = [];
        $goodsForm = \app\forms\pc\goods\GoodsForm::getInstance();
        if ($this->sort == 5) {
            $newGoodsList = [];
            foreach ($list as $item) {
                $time = strtotime($item['updated_at']);
                $date = date('Y-m-d', $time);
                $m = date('m', $time);
                $d = date('d', $time);
                $newGoodsList[$date]['label'] = $m . '月' . $d . '日';
                $newGoodsList[$date]['value'] = $date;

                $goodsForm->goods = $item;
                $newItem = $goodsForm->getDetails();
                $newItem['goods_stock'] = $item->goods_stock;
                $newItem['status'] = $item->status;
                $newGoodsList[$date]['goods_list'][] = $newItem;
            }
            $newList = array_values($newGoodsList);
        }else{
            foreach ($list as $item) {
                $goodsForm->goods = $item;
                $newItem = $goodsForm->getDetails();
                $newItem['goods_stock'] = $item->goods_stock;
                $newItem['status'] = $item->status;
                $newList[] = $newItem;
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $newList,
                'pagination' => $form->pagination,
            ]
        ];
    }
}
