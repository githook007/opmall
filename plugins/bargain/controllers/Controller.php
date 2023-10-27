<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/9
 * Time: 17:07
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\controllers;



use app\plugins\bargain\Plugin;

class Controller extends \app\plugins\Controller
{
    public $sign;

    public function init()
    {
        parent::init();
        $this->sign = (new Plugin())->getName();
    }
}
