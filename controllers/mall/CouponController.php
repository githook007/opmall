<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\forms\mall\coupon\CouponForm;
use app\forms\mall\coupon\CouponUseLogForm;

class CouponController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionDestroy()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->destroy());
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
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

    public function actionSend()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $form->send();
            } else {
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('send');
        }
    }

    //切换领劵中心
    public function actionEditCenter()
    {
        if (\Yii::$app->request->isPost) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->editCenter();
        }
    }

    // 搜索
    public function actionSearchGoods()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->searchGoods());
        }
    }

    public function actionSearchUser()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->searchUser());
        }
    }

    public function actionSearchCat()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->searchCat());
        }
    }

    public function actionOptions()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new CouponForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getOptions());
        }
    }

    /**
     * 使用记录
     */
    public function actionUseLog()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new CouponUseLogForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->search());
            } else {
                $form = new CouponUseLogForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->search());
            }
        } else {
            return $this->render('use-log');
        }
    }
}
