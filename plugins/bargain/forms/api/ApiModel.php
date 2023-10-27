<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/18
 * Time: 14:27
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\forms\api;


use app\models\Mall;
use app\models\Model;
use app\models\User;

/**
 * @property Mall $mall
 * @property User $user
 */
class ApiModel extends Model
{
    protected $mall;
    protected $user;

    public function setMall($val)
    {
        $this->mall = $val;
    }

    public function setUser($val)
    {
        $this->user = $val;
    }
}
