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

class WeekState extends BaseState
{
    public function setJob()
    {
        $nextMonday = strtotime('next monday');
        $delay = $nextMonday - time();
        \Yii::$app->queue->delay($delay)->push(new ClearContinueJob([
            'mall' => $this->common->mall
        ]));
    }

    public function clearContinue()
    {
        $week = date('N');
        $count = 0;
        if ($week == 1) {
            $count = $this->common->clearContinue();
        }
        return $count;
    }
}
