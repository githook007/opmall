<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\forms\mall\wlhulian\EditForm;
use app\forms\mall\wlhulian\IndexForm;
use app\forms\mall\wlhulian\OrderForm;
use app\forms\mall\wlhulian\OrderPayForm;
use app\forms\mall\wlhulian\OrderSendForm;

class WlhulianController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new IndexForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionSaveSetting()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new EditForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->save());
        }
    }

    public function actionPreviewOrder()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderPayForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->save());
        }
    }

    public function actionQueryOrder()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new IndexForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->queryOrder());
        }
    }

    public function actionSendData()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->search());
        }
    }

    public function actionSendOrder()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderSendForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->send());
        }
    }
}
