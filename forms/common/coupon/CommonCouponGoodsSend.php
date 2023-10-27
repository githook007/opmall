<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/4/15
 * Time: 16:17
 */

namespace app\forms\common\coupon;

use app\models\Mall;
use app\models\Model;
use app\models\OrderDetail;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * @property Mall $mall
 * @property User $user
 */
class CommonCouponGoodsSend extends Model
{
    public $mall;
    public $user;
    public $order_id;

    public function useSend($coupon_id)
    {
        $commonCoupon = new CommonCoupon(['coupon_id' => $coupon_id], false);
        $coupon = $commonCoupon->getDetail();
        if ($coupon['use_obtain'] != 1) {
            throw new \Exception('非赠送优惠券');
        }
        $commonCoupon->mall = $this->mall;
        $commonCoupon->user = $this->user;

        $useCoupon = new CouponMallRelation($coupon, $this->order_id, CouponMallRelation::TYPE_COUPON);
        if ($status = $commonCoupon->receive($coupon, $useCoupon, '优惠券使用赠送')) {
            return $this->couponFormat($coupon);
        }
        return null;
    }


    public function send()
    {
        $couponList = [];

        $goodsList = OrderDetail::find()
            ->with('goodsCoupon.goodsCoupons')
            ->where([
                'is_delete' => 0,
                'order_id' => $this->order_id
            ])->all();

        if (!$goodsList) {
            throw new \Exception('商品不存在，无效的order_id');
        }
        /** @var OrderDetail $item */
        foreach ($goodsList as $item) {
            if (empty($item->goodsCoupon)) {
                continue;
            }

            $commonCoupon = new CommonCoupon();
            $commonCoupon->mall = $this->mall;
            $commonCoupon->user = $this->user;

            foreach ($item->goodsCoupon as $goodsCoupon) {
                $coupon = $goodsCoupon->goodsCoupons;
                if ($coupon->is_delete !== 0) {
                    \Yii::warning('优惠券（id：'.$coupon->id.'）已被删除');
                    continue;
                }
                if($coupon->end_time != '0000-00-00 00:00:00'){
                    if($coupon->end_time <= mysql_timestamp()){
                        \Yii::warning('优惠券（id：'.$coupon->id.'）已过期');
                        continue;
                    }
                }
                $useCoupon = new CouponMallRelation($coupon, $this->order_id, CouponMallRelation::GOODS_COUPON);
                for ($i = 0; $i < bcmul($item->num, $goodsCoupon->num); $i++) {
                    if ($commonCoupon->receive($coupon, $useCoupon, '商品赠送优惠券')) {
                        $couponList[] = $this->couponFormat($coupon);
                    }
                }
            }
        }
        return $couponList;
    }


    private function couponFormat($coupon)
    {
        $newCoupon = ArrayHelper::toArray($coupon);
        if ($newCoupon['expire_type'] == 1) {
            $newCoupon['desc'] = "本券有效期为发放后{$newCoupon['expire_day']}天内";
        } else {
            $newCoupon['desc'] = "本券有效期" . $newCoupon['begin_time'] . "至" . $newCoupon['end_time'];
        }
        $newCoupon['content'] = '限';
        $newCoupon['page_url'] = '/pages/goods/list?coupon_id=' . $coupon->id;
        if ($coupon->appoint_type == 1) {
            $catList = [];
            foreach ($coupon->cat as $cat) {
                $catList[] = $cat->name;
            }
            $newCoupon['content'] .= implode('、', $catList) . '使用';
        } elseif ($coupon->appoint_type == 2) {
            $goodsWarehouseList = [];
            foreach ($coupon->goodsWarehouse as $goodsWarehouse) {
                $goodsWarehouseList[] = $goodsWarehouse->name;
                $newCoupon['content'] .= $goodsWarehouse->name . '、';
            }
            $newCoupon['content'] .= implode('、', $goodsWarehouseList) . '使用';
        } elseif ($coupon->appoint_type == 4) {
            $newCoupon['content'] = '仅限当面付使用';
            $newCoupon['page_url'] = '/plugins/scan_code/index/index';
        } else {
            $newCoupon['content'] = '全场通用';
        }
        /** 发放优惠券需要 */
        $newCoupon['share_type'] = 4;
        return $newCoupon;
    }
}
