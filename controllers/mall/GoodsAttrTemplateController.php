<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\controllers\mall;


use app\forms\mall\goods\GoodsAttrTemplateForm;

class GoodsAttrTemplateController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsAttrTemplateForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->get());
        } else {
            return $this->render('index');
        }
    }

    public function actionPost()
    {
        if (\Yii::$app->request->isPost) {
            $form = new GoodsAttrTemplateForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->save());
        }
    }

    public function actionDestroy()
    {
        if (\Yii::$app->request->isPost) {
            $form = new GoodsAttrTemplateForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->destroy());
        }
    }
}