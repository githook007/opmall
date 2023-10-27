<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/26
 * Time: 17:06
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\forms;


use app\models\Mall;

/**
 * @property Mall $mall
 */
class Model extends \app\models\Model
{
    protected $mall;

    public function setMall($val)
    {
        $this->mall = $val;
    }
}
