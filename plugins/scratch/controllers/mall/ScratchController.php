<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scratch\controllers\mall;

use app\plugins\Controller;
use app\plugins\scratch\forms\mall\ScratchForm;

class ScratchController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ScratchForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ScratchForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            } else {
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('edit');
        }
    }

    public function actionDestory()
    {
        if ($id = \Yii::$app->request->post('id')) {
            $form = new ScratchForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->destroy());
        }
    }
    public function actionSearch()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ScratchForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->search());
        }
    }
    public function actionEditStatus()
    {
        if (\Yii::$app->request->isPost) {
            $form = new ScratchForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->editStatus());
        }
    }
    public function actionEditStock()
    {
        if (\Yii::$app->request->isPost) {
            $form = new ScratchForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->editStock());
        }
    }
}
