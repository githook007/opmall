<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\teller\forms\web;

use app\bootstrap\response\ApiCode;
use app\forms\common\goods\CommonGoods;
use app\forms\common\goods\CommonGoodsDetail;
use app\helpers\ArrayHelper;
use app\models\Goods;
use app\models\GoodsAttr;
use app\models\GoodsWarehouse;
use app\models\Model;
use app\models\User;
use app\plugins\teller\forms\common\CommonTellerSetting;
use app\plugins\teller\models\TellerCashier;

class TellerGoodsForm extends Model
{
    public $goods_id;
    public $user_id;
    public $bar_code;

    public function rules()
    {
        return [
            [['goods_id', 'user_id'], 'integer'],
            [['bar_code'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'bar_code' => '条形码',
        ];
    }

    //GET
    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $res = $this->getGoods();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'goods'=> $res,
                ],
            ];
        }catch(\Exception $exception) {            
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine()
            ];
        }
    }

    private function getGoods()
    {
        if (!$this->user_id) {
            $setting = (new CommonTellerSetting())->search();
            $this->user_id = $setting['user_id'];
        }
        $user = User::find()->andWhere(['id' => $this->user_id, 'is_delete' => 0])->one();

        if (!$user) {
            throw new \Exception('用户不存在');
        }
        
        $form = new CommonGoodsDetail();
        $form->user = $user;
        $form->mall = \Yii::$app->mall;
        $goods = $form->getGoods($this->goods_id);
        if (!$goods) {
            throw new \Exception('商品不存在');
        }
        if ($goods->status != 1) {
            throw new \Exception('商品未上架');
        }

        $form->goods = $goods;
        $mallGoods = CommonGoods::getCommon()->getMallGoods($goods->id);
        $form->setMember($mallGoods->is_negotiable == 0);
        $form->setShare($mallGoods->is_negotiable == 0);
        $form->setIsTemplateList(false);

        $res = $form->getAll([
            'attr', 'goods_num', 'goods_no', 'goods_weight', 'attr_group', 'option', 'services',
            'cards', 'price_min', 'price_max', 'pic_url', 'share', 'sales', 'favorite', 'goods_marketing',
            'goods_marketing_award', 'vip_card_appoint', 'goods_coupon_center', 'goods_activity',
            'guarantee_title', 'guarantee_pic'
        ]);
        $res = array_merge($res, [
            'is_quick_shop' => $mallGoods->is_quick_shop,
            'is_sell_well' => $mallGoods->is_sell_well,
            'is_negotiable' => $mallGoods->is_negotiable,
        ]);

        //图片替换
        $temp = [];
        foreach ($res['attr'] as $v) {
            foreach ($v['attr_list'] as $w) {
                if (!isset($temp[$w['attr_id']])) {
                    $temp[$w['attr_id']] = $v['pic_url'];
                }
            }
        }

        foreach ($res['attr_groups'] as $k => $v) {
            foreach ($v['attr_list'] as $l => $w) {
                $res['attr_groups'][$k]['attr_list'][$l]['pic_url'] = $temp[$w['attr_id']] ?: "";
            }
        }

        return $res;
    }

    public function barCodeSearch()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $goodsAttr = GoodsAttr::find()->andWhere(['bar_code' => $this->bar_code])->one();
            if (!$goodsAttr) {
                throw new \Exception('条形码不存在');
            }

            $this->goods_id = $goodsAttr->goods_id;
            $res = $this->getGoods();

            $attr = [];
            foreach ($res['attr'] as $item) {
                if ($item['bar_code'] == $this->bar_code) {
                    $attr = $item;
                }
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'goods'=> [
                        'num' => 1,
                        'name' => $res['name'],
                        'goods_attr_id' => $attr['id'],
                        'cart_id' => 0,
                        'cover_pic' => $res['cover_pic'],
                        'price' => $attr['price'],
                        'id' => $res['id'],
                        'selectAttr' => $attr,
                        'attr' => $attr['attr_list']
                    ],
                ],
            ];
        }catch(\Exception $exception) {            
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine()
            ];
        }
    }
}
