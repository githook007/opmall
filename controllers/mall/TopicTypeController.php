<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c) 2018 hook007
* author: opmall
*/

namespace app\controllers\mall;

use app\forms\mall\topic\TopicTypeForm;

class TopicTypeController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TopicTypeForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = json_decode(\Yii::$app->request->get('search'), true);
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionDestroy()
    {
        if ($id = \Yii::$app->request->post('id')) {
            $form = new TopicTypeForm();
            $form->id = $id;
            return $this->asJson($form->destroy());
        }
    }

    public function actionSwitchStatus()
    {
        if(\Yii::$app->request->isPost) {
            $form = new TopicTypeForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->switchStatus());
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TopicTypeForm();
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
}
