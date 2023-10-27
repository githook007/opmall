<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\admin;

use app\controllers\behaviors\SuperAdminFilter;
use app\forms\admin\logistics\MallForm;
use app\forms\admin\logistics\WlEditForm;
use app\forms\admin\logistics\WlForm;
use app\forms\admin\logistics\WlPriceEditForm;
use app\forms\admin\logistics\WlStoreEditForm;

class LogisticsController extends AdminController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'superAdminFilter' => [
                'class' => SuperAdminFilter::class,
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new WlEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            } else {
                $form = new WlForm();
                return $form->getSetting();
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionStore()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new WlStoreEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            } else {
                $form = new WlForm();
                return $form->getStoreSetting();
            }
        } else {
            return $this->render('store');
        }
    }

    public function actionMall()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->getList();
        } else {
            return $this->render('mall');
        }
    }

    public function actionMallSearch()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->search();
        }
    }

    public function actionAddMall()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->add();
        }
    }

    public function actionMoney()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->money();
        }
    }

    public function actionMallDelete()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->delete();
        }
    }

    public function actionPrice()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new WlPriceEditForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            } else {
                $form = new WlForm();
                $form->attributes = \Yii::$app->request->get();
                return $form->getPriceSetting();
            }
        } else {
            return $this->render('price');
        }
    }

    public function actionPreviewOrder()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->order());
        }
    }

    public function actionQueryOrder()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MallForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->queryOrder());
        }
    }
}
