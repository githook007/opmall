<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\controllers\mall;


use app\forms\mall\copyright\CopyrightEditForm;
use app\forms\mall\copyright\CopyrightForm;

class CopyrightController extends MallController
{
    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new CopyrightForm();
                $res = $form->getDetail();

                return $this->asJson($res);
            } else {
                $form = new CopyrightEditForm();
                $form->data = \Yii::$app->request->post('form');
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
        } else {
            return $this->render('setting');
        }
    }
}
