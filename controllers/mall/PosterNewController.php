<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\controllers\mall;


use app\forms\mall\poster\PosterNewForm;

class PosterNewController extends MallController
{
    public function actionGet()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PosterNewForm();
            return $this->asJson($form->get());
        }
        return $this->render('setting');
    }

    public function actionPost()
    {
        if (\Yii::$app->request->isPost) {
            $form = new PosterNewForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->save());
        }
    }
}