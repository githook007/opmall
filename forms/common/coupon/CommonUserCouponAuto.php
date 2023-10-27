<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/13
 * Time: 17:00
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\coupon;


use app\models\Coupon;
use app\models\CouponAutoSend;
use app\models\UserCouponAuto;

class CommonUserCouponAuto extends UserCouponData
{
    public $coupon;
    public $autoSend;
    public $userCoupon;

    public function __construct(Coupon $coupon, CouponAutoSend $autoSend)
    {
        $this->coupon = $coupon;
        $this->autoSend = $autoSend;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
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