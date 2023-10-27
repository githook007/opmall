<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */

namespace app\plugins\minishop\controllers\mall;

use app\plugins\Controller;
use app\plugins\minishop\forms\SettingForm;

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
                return $this->asJson($form->getSetting());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionSearchUser(){
        $form = new SettingForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->searchUser());
    }
}
