<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\exchange\controllers\mall;

use app\plugins\Controller;

class OrderController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
