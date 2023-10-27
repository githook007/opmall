<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/28
 * Time: 3:19 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\common;

use app\forms\common\coupon\UserCouponData;

class ScrmUserCouponData extends UserCouponData
{
    public function save()
    {
        if ($this->check($this->coupon)) {
            $this->coupon->updateCount(1, 'sub');
        }
        $userCouponCenter = new UserCouponAuto();
        $userCouponCenter->user_coupon_id = $this->userCoupon->id;
        $userCouponCenter->auto_coupon_id = $this->autoSend->id;
        return $userCouponCenter->save();
    }
}
