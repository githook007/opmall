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

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new IntegralMallEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $this->asJson($form->save());
            } else {
                $form = new IntegralMallForm();
                return $this->asJson($form->getSetting());
            }
        } else {
            return $this->render('index');
        }
    }
}
