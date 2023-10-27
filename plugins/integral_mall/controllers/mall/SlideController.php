<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\controllers\mall;


use app\plugins\Controller;
use app\plugins\integral_mall\forms\mall\IntegralMallEditForm;
use app\plugins\integral_mall\forms\mall\IntegralMallForm;

class SlideController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
