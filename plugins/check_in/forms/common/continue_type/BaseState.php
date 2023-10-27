<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/15
 * Time: 16:02
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms\common\continue_type;


use app\plugins\check_in\forms\common\Common;
use app\plugins\check_in\forms\Model;

/**
 * @property Common $common;
 */
abstract class BaseState extends Model
{
    public $common;

    abstract public function setJob();

    abstract public function clearContinue();
}
