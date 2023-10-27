<?php

namespace app\forms\pc\cart;

use app\models\Cart;
use app\models\Goods;
use app\models\MallMembers;
use app\models\UserIdentity;
use yii\helpers\ArrayHelper;

class CartForm extends \app\forms\api\cart\CartForm
{
    public function handleAttr(&$newItem, Cart $item, UserIdentity $userIdentity){
        $newItem['reduce_price'] = 0;
        if ($item->attrs) {
            $newItem['attrs'] = ArrayHelper::toArray($item->attrs);
            // 还存在的商品
            $attrList = (new Goods())->signToAttr($item->attrs->sign_id, $item->goods->attr_groups);
            foreach ($attrList as $attrItem){
                $newItem['attr_text'][] = "{$attrItem['attr_group_name']}：{$attrItem['attr_name']}";
            }
            $newItem['attr_text'] = implode("，", $newItem['attr_text']);
            $newItem['attr_str'] = 0;
            if ($item->attr_info) {
                try {
                    $attrInfo = \Yii::$app->serializer->decode($item->attr_info);
                    $reducePrice = $attrInfo['price'] - $item->attrs->price;
                    if ($attrInfo['price'] - $item->attrs->price) {
                        $newItem['reduce_price'] = $reducePrice;
                    }
                } catch (\Exception $exception) {}
            }
        } else {
            $newItem['attrs'] = $item->attrs;
            $newItem['attr_str'] = 1;
        }
        // 购物车显示会员价
        if ($userIdentity && $userIdentity->member_level && $item->goods->is_level && $item->mch_id == 0 && $item->attrs) {
            if ($item->goods->is_level_alone) {
                foreach ($item->attrs->memberPrice as $mItem) {
                    if ($mItem->level == $userIdentity->member_level) {
                        $newItem['attrs']['price'] = $mItem['price'] > 0 ? $mItem['price'] : $item->attrs->price;
                        break;
                    }
                }
            } else {
                /** @var MallMembers $member */
                $member = MallMembers::find()->where([
                    'status' => 1,
                    'is_delete' => 0,
                    'level' => $userIdentity->member_level,
                    'mall_id' => \Yii::$app->mall->id
                ])->one();
                if ($member) {
                    $newItem['attrs']['price'] = round(($member->discount / 10) * $item->attrs->price, 2);
                }
            }
        }
    }
}
