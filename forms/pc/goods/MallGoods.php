<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\goods;

use app\models\Model;
use app\models\OrderDetail;

class MallGoods extends Model
{
    /**
     * PC端 处理订单展示的商品数据
     * @param OrderDetail $orderDetail
     * @return array
     */
    public static function getGoodsData($orderDetail)
    {
        // 暂时先处理下, TODO 应该限制orderDetail类型
        if (is_array($orderDetail)) {
            $orderDetail = (object)$orderDetail;
        }

        $goodsInfo = [];
        try {
            $goodsAttrInfo = \Yii::$app->serializer->decode($orderDetail->goods_info);
            $goodsInfo['name'] = isset($goodsAttrInfo['goods_attr']['name']) ? $goodsAttrInfo['goods_attr']['name'] : '';
            $attrList = isset($goodsAttrInfo['attr_list']) ? $goodsAttrInfo['attr_list'] : [];
            $goodsInfo['attr_text'] = [];
            foreach ($attrList as $attrItem){
                $goodsInfo['attr_text'][] = "{$attrItem['attr_group_name']}：{$attrItem['attr_name']}";
            }
            $goodsInfo['attr_text'] = implode("，", $goodsInfo['attr_text']);
            $goodsInfo['pic_url'] = isset($goodsAttrInfo['goods_attr']['pic_url']) && $goodsAttrInfo['goods_attr']['pic_url'] ? $goodsAttrInfo['goods_attr']['pic_url'] : $goodsAttrInfo['goods_attr']['cover_pic'];

            $goodsInfo['num'] = isset($orderDetail->num) ? $orderDetail->num : 0;
            $goodsInfo['total_original_price'] = isset($orderDetail->total_original_price) ? $orderDetail->total_original_price : 0;
            $goodsInfo['total_price'] = isset($orderDetail->total_price) ? $orderDetail->total_price : 0;
            $goodsInfo['member_discount_price'] = isset($orderDetail->member_discount_price) ? $orderDetail->member_discount_price : 0;
            $goodsInfo['goods_attr'] = $goodsAttrInfo['goods_attr'];

            $goodsInfo['is_show_send_type'] = 1;
            $goodsInfo['is_can_apply_sales'] = 1;
            $goodsInfo['is_show_express'] = 1;
            $goodsInfo['goods_type'] = 'goods';
            if (isset($goodsAttrInfo['goods_attr']['goods_type'])) {
                $goodsInfo['is_show_send_type'] = $goodsAttrInfo['goods_attr']['goods_type'] == 'ecard' ? 0 : 1;
                $goodsInfo['is_can_apply_sales'] = $goodsAttrInfo['goods_attr']['goods_type'] == 'ecard' ? 0 : 1;
                $goodsInfo['is_show_express'] = $goodsAttrInfo['goods_attr']['goods_type'] == 'ecard' ? 0 : 1;
                $goodsInfo['goods_type'] = $goodsAttrInfo['goods_attr']['goods_type'];
            }

        } catch (\Exception $exception) {
            // dd($exception);
        }
        return $goodsInfo;
    }
}
