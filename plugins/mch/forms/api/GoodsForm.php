<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\forms\common\goods\CommonGoodsDetail;
use app\forms\common\goods\CommonGoodsList;
use app\forms\common\goods\GoodsAuth;
use app\models\Model;
use app\models\ShareSetting;
use app\plugins\mch\models\Goods;
use app\plugins\mch\models\MchMallSetting;
use app\plugins\mch\models\MchSetting;
use app\forms\common\goods\CommonGoods;

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
    public $mch_status = -1;
    public $cat_id;

    public function rules()
    {
        return [
            [['mch_id'], 'required'],
            [['keyword'], 'string'],
            [['id', 'mch_id', 'sort', 'sort_type', 'is_sold_out', 'mch_status', 'cat_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
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
        if ($this->status != 2) {
            $form->status = $this->status;
        }
        if ($this->sort == 4) {
            $form->is_sales = 1;
        }
        $form->sign = \Yii::$app->plugin->getCurrentPlugin()->getName();
        $form->is_sold_out = $this->is_sold_out ?: null;
        $form->mch_status = $this->mch_status;
        if(!$this->status && $this->mch_status == 2){
            $form->status = 1;
        }
        $form->relations = ['goodsWarehouse', 'attr', 'mchGoods'];
        $list = $form->search();

        $newList = [];

        if ($this->sort == 5) {
            $newGoodsList = [];
            /* @var Goods[] $list */
            foreach ($list as $item) {
                $time = strtotime($item['updated_at']);
                $date = date('Y-m-d', $time);
                $m = date('m', $time);
                $d = date('d', $time);
                $newGoodsList[$date]['label'] = $m . '月' . $d . '日';
                $newGoodsList[$date]['value'] = $date;

                $newItem = $form->getGoodsData($item);
                $newItem['goods_stock'] = $item->goods_stock;
                $newItem['status'] = $item->status;
                $newItem['mch_goods']['id'] = $item->mchGoods->id;
                $newItem['mch_goods']['status'] = $item->mchGoods->status;
                $newGoodsList[$date]['goods_list'][] = $newItem;
            }
            $newList = array_values($newGoodsList);
        }else{
            /* @var Goods[] $list */
            foreach ($list as $item) {
                $newItem = $form->getGoodsData($item);
                $newItem['goods_stock'] = $item->goods_stock;
                $newItem['status'] = $item->status;
                $newItem['mch_goods']['id'] = $item->mchGoods->id;
                $newItem['mch_goods']['status'] = $item->mchGoods->status;
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

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $form = new CommonGoodsDetail();
            $form->mall = \Yii::$app->mall;
            $form->mch_id = $this->mch_id;
            $form->user = \Yii::$app->user->identity;
            $goods = $form->getGoods($this->id);

            if (!$goods) {
                throw new \Exception('商品不存在');
            }
            if (!GoodsAuth::create($goods->sign)->checkShowAuth($goods)) {
                throw new \Exception('您暂无权限查看商品');
            }

            $isShare = true;
            /** @var ShareSetting $shareSetting */
            $shareSetting = ShareSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'key' => 'level']);
            $mchMallSetting = MchMallSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'mch_id' => $this->mch_id]);
            $mchSetting = MchSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'mch_id' => $this->mch_id]);
            if (!$shareSetting || ($shareSetting && $shareSetting->value == 0)) {
                $isShare = false;
            }
            if (!$mchMallSetting || ($mchMallSetting && $mchMallSetting->is_share == 0)) {
                $isShare = false;
            }
            if (!$mchSetting || ($mchSetting && $mchSetting->is_share == 0)) {
                $isShare = false;
            }

            $form->goods = $goods;
            $form->setMember(false);
            $form->setShare($isShare);
            $form->setIsLimitBuy(true);
            // @czs
            $ignore = [];
            if (!$mchMallSetting || $mchMallSetting->is_coupon == 0) { // 关闭优惠券
                $ignore = ["goods_coupon_center"];
            }
            $res = $form->getAll([], $ignore);
            // end
            $salesCount = $this->getSales($res['virtual_sales'], $goods);
            $res['sales'] = $salesCount == -1 ? '' : $salesCount;

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $res,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function getCatStyle()
    {
        $option = CommonAppConfig::getAppCatStyle($this->mch_id);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $option
            ]
        ];
    }

    private function getSales($virtualSales, $goods)
    {
        try {
            $setting = \Yii::$app->mall->getMallSetting(['is_sales']);
            $isSales = $setting['is_sales'];
        } catch (\Exception $exception) {
            $isSales = 1;
        }
        $goodsSales = $goods->sales;

        $salesCount = ($virtualSales + $goodsSales) > 10000 ? (float)number_format(($virtualSales + $goodsSales) / 10000, 1) . 'w' : $virtualSales + $goodsSales;
        // -1 不显示销量
        return $isSales == 1 ? $salesCount : -1;
    }

    public function show()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $common = CommonGoods::getCommon();
            $common->mch_id = $this->mch_id;
            $detail = $common->getGoodsDetail($this->id);

            $mallGoods = $common->getMallGoods($this->id);
            if (!$mallGoods) {
                throw new \Exception('数据异常，mallGoods商品不存在');
            }
            $detail['status'] = intval($detail['status']);
            $detail = array_merge($detail, [
                'is_quick_shop' => $mallGoods->is_quick_shop,
                'is_sell_well' => $mallGoods->is_sell_well,
                'is_negotiable' => $mallGoods->is_negotiable,
            ]);
            $detail = array_merge($detail, $common->getDistrictPrice($detail['attr']));

            if ($detail) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '请求成功',
                    'data' => [
                        'detail' => $detail,
                    ],
                ];
            }

            throw new \Exception('请求失败');
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ],
            ];
        }
    }
}