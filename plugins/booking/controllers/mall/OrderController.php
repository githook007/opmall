<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\booking\controllers\mall;

use app\plugins\booking\forms\mall\BookingOrderForm;
use app\plugins\booking\forms\mall\OrderForm;
use app\plugins\Controller;
use app\forms\mall\order\OrderDestroyForm;
use app\forms\mall\order\OrderDetailForm;

class OrderController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->search());
        } else {
            if (\Yii::$app->request->post('flag') === 'EXPORT') {
                $fields = explode(',', \Yii::$app->request->post('fields'));
                $form = new BookingOrderForm();
                $form->attributes = \Yii::$app->request->post();
                $form->fields = $fields;
                $form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->search();
                return false;
            } else {
                return $this->render('index');
            }
        }
    }

    //订单详情
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
