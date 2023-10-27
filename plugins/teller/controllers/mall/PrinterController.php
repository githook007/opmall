<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c) 2018 hook007
* author: opmall
*/

namespace app\plugins\teller\controllers\mall;

use app\plugins\Controller;
use app\plugins\teller\forms\mall\TellerPrinterForm;
use app\plugins\teller\forms\mall\TellerPrinterModifyForm;
use app\plugins\teller\forms\mall\TellerPrinterStoreForm;

class PrinterController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TellerPrinterForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }

    public function actionOptions()
    {
        $form = new TellerPrinterForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getOptions());
    }

    public function actionStore()
    {
        $form = new TellerPrinterStoreForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    public function actionModify()
    {
        $form = new TellerPrinterModifyForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->modify());
    }

    // 详情
    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TellerPrinterForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getDetail());
        } else {
            return $this->render('edit');
        }
    }

    public function actionDelete()
    {
        $form = new TellerPrinterForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->delete());
    }

    public function actionUpdateStatus()
    {
        $form = new TellerPrinterForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->updateStatus());
    }
}
