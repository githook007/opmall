<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/7
 * Time: 17:36
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\coupon;


use app\models\Coupon;
use app\models\User;

class UserCouponMember extends UserCouponData
{
    public $coupon;
    public $user;
    public $userCoupon;

    public function __construct(Coupon $coupon, User $user)
    {
        $this->coupon = $coupon;
        $this->user = $user;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if ($this->check($this->coupon)) {
            $this->coupon->updateCount(1, 'sub');
        } else {
            return false;
        }
        $userCouponCenter = new \app\models\UserCouponMember();
        $userCouponCenter->mall_id = $this->coupon->mall_id;
        $userCouponCenter->user_id = $this->user->id;
        $userCouponCenter->user_coupon_id = $this->userCoupon->id;
        $userCouponCenter->member_level = $this->user->identity->member_level;
        $userCouponCenter->is_delete = 0;
        return $userCouponCenter->save();
    }
}
