<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/5/24
 * Time: 11:27
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms\common\award;


class TotalAward extends BaseAward
{
    /**
     * @return mixed
     * @throws \Exception
     * 校验
     */
    public function check()
    {
        $common = $this->common;
        $checkInUser = $common->getCheckInUser($this->user);
        if ($checkInUser->total < $this->day) {
            throw new \Exception('用户累计签到天数未达到领取条件');
        }
        $sign = $common->getSignInByDay($this->status, $this->day, $this->user);
        if ($sign) {
            throw new \Exception('已领取奖励');
        }
        return true;
    }
}
