<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\diy\controllers\mall;


use app\plugins\Controller;
use app\plugins\diy\forms\mall\TemplateForm;


class HomeController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TemplateForm();
            return $this->asJson($form->getHome());
        } else {
            return $this->render('index');
        }
    }
}