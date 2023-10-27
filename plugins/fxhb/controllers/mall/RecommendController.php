<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\fxhb\controllers\mall;


use app\plugins\Controller;
use app\plugins\fxhb\forms\mall\RecommendForm;

class RecommendController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new RecommendForm();
                $form->data = \Yii::$app->request->post('form');
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new RecommendForm();
                $res = $form->getSetting();

                return $this->asJson($res);
            }
        } else {
            return $this->render('index');
        }
    }
}