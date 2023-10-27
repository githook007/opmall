<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/15
 * Time: 15:54
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms\common\continue_type;


class UnlimitedState extends BaseState
{
    public function setJob()
    {
    }

    public function clearContinue()
    {
        return 0;
    }
}
