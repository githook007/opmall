<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scan_code_pay\controllers\mall;


use app\forms\mall\order\OrderDestroyForm;
use app\forms\mall\order\OrderDetailForm;
use app\plugins\Controller;
use app\plugins\scan_code_pay\forms\mall\OrderForm;
use app\plugins\scan_code_pay\forms\mall\ScanCodePayOrderForm;

class OrderController extends Controller
{
    public function actionIndex()
    {
        $sign = \Yii::$app->plugin->getCurrentPlugin()->getName();
        if (\Yii::$app->request->isAjax) {
            $form = new OrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->setSign($sign)->search());
        } else {
            return $this->render('index');
        }
    }

    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderDetailForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->search());
        } else {
            return $this->render('detail');
        }
    }

    //清空回收站
    public function actionDestroyAll()
    {
        if (\Yii::$app->request->isPost) {
            $form = new OrderDestroyForm();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->destroyAll());
        }
    }
}