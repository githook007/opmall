<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\controllers\mall;

use app\plugins\Controller;
use app\plugins\exchange\forms\mall\SettingForm;

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new SettingForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            } else {
                $form = new SettingForm();
                return $this->asJson($form->get());
            }
        } else {
            return $this->render('index');
        }
    }
}
