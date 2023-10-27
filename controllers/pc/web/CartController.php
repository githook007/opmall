<?php
namespace app\controllers\pc\web;

use app\controllers\pc\web\filters\LoginFilter;
use app\forms\api\cart\CartAddForm;
use app\forms\pc\cart\CartDeleteForm;
use app\forms\api\cart\CartEditForm;
use app\forms\pc\cart\CartForm;

class CartController extends CommonController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class
            ],
        ]);
    }

    public function actionList()
    {
        $form = new CartForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    public function actionAdd()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CartAddForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CartEditForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->save());
        }
    }

    public function actionDelete()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CartDeleteForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->save();
        }
    }

    public function actionDeleteAll()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CartDeleteForm();
            return $form->delAll();
        }
    }
}
