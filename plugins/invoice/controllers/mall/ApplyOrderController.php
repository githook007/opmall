<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: Jayi
 */

namespace app\plugins\invoice\controllers\mall;

use app\plugins\Controller;
use app\plugins\invoice\forms\mall\ApplyOrder;

class ApplyOrderController extends Controller
{
    /**
     * 列表-待审核
     */
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ApplyOrder();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    /**
     * 列表-已经开票
     */
    public function actionSuccess()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ApplyOrder();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->getList());
        } else {
            return $this->render('success');
        }
    }

    /**
     * 详情
     */
    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new ApplyOrder();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getDetail(\Yii::$app->request->get()['order_id']));
        } else {
            return $this->render('detail');
        }
    }

    /**
     * 同意开票
     */
    public function actionAgree()
    {
        if (\Yii::$app->request->isPost) {
            $form = new ApplyOrder();
            return $form->agreeRefusal(\Yii::$app->request->post()['id']);
        }
    }

    /**
     * 拒绝开票
     */
    public function actionRefuse()
    {
        if (\Yii::$app->request->isPost) {
            $form = new ApplyOrder();
            return $form->refuseRefusal(\Yii::$app->request->post());
        }
    }

}
