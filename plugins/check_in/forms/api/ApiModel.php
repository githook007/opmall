<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/27
 * Time: 9:55
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms\api;


use app\models\User;
use app\plugins\check_in\forms\Model;

/**
 * @property User $user
 */
class ApiModel extends Model
{
    protected $user;

    public function setUser($val)
    {
        $this->user = $val;
    }
}
