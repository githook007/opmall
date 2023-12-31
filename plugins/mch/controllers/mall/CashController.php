<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\controllers\mall;


use app\plugins\Controller;
use app\plugins\mch\forms\mall\CashEditForm;
use app\plugins\mch\forms\mall\CashForm;

class CashController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new CashForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getList());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionEdit()
    {
        $form = new CashEditForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    public function actionTransfer()
    {
        $form = new CashEditForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->transfer());
    }
}
