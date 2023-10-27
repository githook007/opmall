<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\teller\controllers\mall;


use app\plugins\Controller;
use app\plugins\teller\forms\mall\TellerSettingEditForm;
use app\plugins\teller\forms\mall\TellerSettingForm;

class IndexController extends Controller
{   
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new TellerSettingEditForm();
                $form->data = \Yii::$app->request->post('form');
                return $this->asJson($form->save());
            } else {
                $form = new TellerSettingForm();
                return $this->asJson($form->getSetting());
            }
        } else {
            return $this->render('index');
        }
    }
}
