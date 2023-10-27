<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:44
 */


namespace app\plugins\app_admin\controllers;



use app\plugins\app_admin\Plugin;

class Controller extends \app\plugins\Controller
{
    public $sign;

    public function init()
    {
        parent::init();
        $this->sign = (new Plugin())->getName();
    }
}
