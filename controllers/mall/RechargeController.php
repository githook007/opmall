<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c) 2018 hook007
* author: opmall
*/

namespace app\controllers\mall;

use app\forms\mall\recharge\RecharegConfigForm;
use app\forms\mall\recharge\RechargeForm;
use app\forms\mall\recharge\RechargePageForm;
use app\forms\mall\recharge\RechargeSettingForm;

class RechargeController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new RechargeForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionDestroy()
    {
        if ($id = \Yii::$app->request->post('id')) {
            $form = new RechargeForm();
            $form->id = $id;
            return $this->asJson($form->destroy());
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new RechargeForm();
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

    public function actionConfig()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new RecharegConfigForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $form->post();
            } else {
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->get());
            }
        } else {
            return $this->render('config');
        }
    }
}
