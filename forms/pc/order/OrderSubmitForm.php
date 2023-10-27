<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\order;

use app\forms\api\order\OrderException;
use app\forms\api\order\OrderGoodsAttr;
use app\models\Goods;

class OrderSubmitForm extends \app\forms\api\order\OrderSubmitForm
{
    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function preview()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        try {
            $data = $this->getAllData();
        } catch (OrderException $orderException) {
            return [
                'code' => 1,
                'msg' => $orderException->getMessage(),
                'error' => [
                    'line' => $orderException->getLine()
                ]
            ];
        }
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function submit()
    {
        $result = parent::submit();
        if(!empty($result['data']['token'])){
            $result['data']['order_token'] = $result['data']['token'];
            unset($result['data']['token']);
        }
        return $result;
    }

    protected function getGoodsItemData($item)
    {
        /** @var Goods $goods */
        $goods = Goods::find()->with('goodsWarehouse')->where([
            'id' => $item['id'],
            'mall_id' => \Yii::$app->mall->id,
            'status' => 1,
            'is_delete' => 0,
        ])->one();

        if (!$goods) {
            throw new OrderException('商品不存在或已下架。');
        }

        // 其他商品特有判断
        $this->checkGoods($goods, $item);

        try {
            /** @var OrderGoodsAttr $goodsAttr */
            $goodsAttr = $this->getGoodsAttr($item['goods_attr_id'], $goods);
            $goodsAttr->number = $item['num'];
        } catch (\Exception $exception) {
            throw new OrderException('无法查询商品`' . $goods->name . '`的规格信息。');
        }

        $attrList = $goods->signToAttr($goodsAttr->sign_id);
        $attr_text = [];
        foreach ($attrList as $attrItem){
            $attr_text[] = "{$attrItem['attr_group_name']}：{$attrItem['attr_name']}";
        }
        $attr_text = implode("，", $attr_text);
        $itemData = [
            'id' => $goods->id,
            'name' => $goods->goodsWarehouse->name,
            'num' => $item['num'],
            'forehead_integral' => $goods->forehead_integral,
            'forehead_integral_type' => $goods->forehead_integral_type,
            'accumulative' => $goods->accumulative,
            'freight_id' => $goods->freight_id,
            'unit_price' => price_format($goodsAttr->original_price),
            'total_original_price' => price_format($goodsAttr->original_price * $item['num']),
            'total_price' => price_format($goodsAttr->price * $item['num']),
            'goods_attr' => $goodsAttr,
            'attr_list' => $attrList,
            'discounts' => $goodsAttr->discount,
            'member_discount' => price_format(0),
            'cover_pic' => $goods->goodsWarehouse->cover_pic,
            'is_level_alone' => $goods->is_level_alone,
            // 规格自定义货币 例如：步数宝的步数币
            'custom_currency' => $this->getCustomCurrency($goods, $goodsAttr),
            'is_level' => $goods->is_level,
            'goods_warehouse_id' => $goods->goods_warehouse_id,
            'sign' => $goods->sign,
            'confine_order_count' => $goods->confine_order_count,
            'form_id' => $goods->form_id,
            'goodsWarehouse' => $goods->goodsWarehouse,
            'attr_text' => $attr_text
        ];
        return $itemData;
    }

    public function extraOrder($order, $mchItem){
        return true;
    }

    protected function getTemplateMessage(){
        return [];
    }
}
