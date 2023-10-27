<?php

namespace app\forms\pc\goods;

use app\bootstrap\response\ApiCode;
use app\forms\common\coupon\CommonCoupon;
use app\forms\common\goods\CommonGoodsList;
use app\models\Goods;
use app\models\Mall;

class GoodsListForm extends \app\forms\api\GoodsListForm
{
    public $limit;

    public function rules()
    {
        return array_merge([
            [['limit'], 'default', 'value' => 100],
            [['cat_id', 'sort', 'sort_type', 'keyword', 'coupon_id'], 'trim'],
        ], parent::rules());
    }

    public function search()
    {
        try {
            $form = new CommonGoodsList();
            if ($this->coupon_id && is_numeric($this->coupon_id)) {
                $commonCoupon = new CommonCoupon([
                    'mall' => \Yii::$app->mall,
                ], false);
                $commonCoupon->coupon_id = $this->coupon_id;
                $coupon = $commonCoupon->getDetail();
                if ($coupon->appoint_type == 2) {
                    $goodsWarehouseList = $coupon->goods;
                    $goodsWarehouseId = [];
                    foreach ($goodsWarehouseList as $goodsWarehouse) {
                        $goodsWarehouseId[] = $goodsWarehouse->id;
                    }
                    $form->goodsWarehouseId = $goodsWarehouseId;
                } elseif ($coupon->appoint_type == 1) {
                    $catList = $coupon->cat;
                    $this->cat_id = [];
                    foreach ($catList as $cats) {
                        $this->cat_id[] = $cats->id;
                    }
                }
                $form->cat_id = $this->cat_id;
            } else {
                $form->cat_id = is_numeric($this->cat_id) ? $this->cat_id : 0;
            }
            $form->sort = $this->sort;
            $form->status = 1;
            $form->sort_type = $this->sort_type;
            $form->keyword = $this->keyword;
            $form->page = $this->page;
            $form->limit = $this->limit;
            $form->mch_id = $this->mch_id ?: 0;
            $form->is_array = false;
            $form->mch_id && $this->sign = 'mch';
            $form->sign = $this->sign ? $this->sign : ['mch', ''];
            $form->isSignCondition = true;
            $form->is_sales = (new Mall())->getMallSettingOne('is_sales');
            $form->relations = ['goodsWarehouse', 'mallGoods'];
            $list = $form->search();
            $newList = [];
            /* @var Goods[] $list */
            foreach ($list as $item) {//
                GoodsForm::getInstance()->goods = $item;
                $newList[] = GoodsForm::getInstance()->getDetails();
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'list' => $newList,
                    'pagination' => $form->pagination,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
