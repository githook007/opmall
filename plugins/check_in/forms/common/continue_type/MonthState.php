<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/15
 * Time: 16:01
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms\common\continue_type;


use app\plugins\check_in\jobs\ClearContinueJob;

class MonthState extends BaseState
{
    public function setJob()
    {
        $nowDate = date('Y-m-01', strtotime(date("Y-m-d")));
        $nextDate = strtotime("$nowDate + 1 month");
        $delay = $nextDate - time();
        \Yii::$app->queue->delay($delay)->push(new ClearContinueJob([
            'mall' => $this->common->mall
        ]));
    }

    public function clearContinue()
    {
        $day = date('j');
        $count = 0;
        if ($day == 1) {
            $count = $this->common->clearContinue();
        }
        return $count;
    }
}
