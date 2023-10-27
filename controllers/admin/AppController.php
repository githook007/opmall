<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/2/20 17:17
 */


namespace app\controllers\admin;


class AppController extends AdminController
{
    public function actionRecycle()
    {
        return $this->render('recycle');
    }
}
