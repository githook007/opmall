<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\diy\forms\api;

use app\forms\common\coupon\UserCouponData;
use app\models\Coupon;
use app\models\User;
use app\plugins\diy\models\DiyAdCoupon;

class AdUserCoupon extends UserCouponData
{
    public $coupon;
    public $user;
    public $userCoupon;

    public function __construct(Coupon $coupon, User $user)
    {
        $this->coupon = $coupon;
        $this->user = $user;
    }

    public function save()
    {
        if ($this->check($this->coupon)) {
            $this->coupon->updateCount(1, 'sub');
        } else {
            return false;
        }
        $userCouponCenter = new DiyAdCoupon();
        $userCouponCenter->mall_id = $this->coupon->mall_id;
        $userCouponCenter->user_id = $this->user->id;
        $userCouponCenter->user_coupon_id = $this->userCoupon->id;
        $userCouponCenter->save();
        return $userCouponCenter->save();
    }

    public function check($coupon)
    {
        return parent::check($coupon);
    }
}