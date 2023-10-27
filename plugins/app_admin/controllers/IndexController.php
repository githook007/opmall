<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:44
 */


namespace app\plugins\app_admin\mall\controllers;


use app\plugins\Controller;

class IndexController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
