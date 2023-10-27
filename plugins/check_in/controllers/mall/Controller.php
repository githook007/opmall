<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/26
 * Time: 13:47
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\controllers\mall;


use app\plugins\check_in\Plugin;

class Controller extends \app\plugins\Controller
{
    public $sign;

    public function init()
    {
        parent::init();
        $this->sign = (new Plugin())->getName();
    }
}
