<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\controllers\mall;

use app\plugins\Controller;
use app\plugins\integral_mall\forms\mall\PosterForm;

class PosterController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PosterForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionSave()
    {
        if (\Yii::$app->request->isPost) {
            $form = new PosterForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
    }
}
