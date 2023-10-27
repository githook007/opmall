<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\BalanceForm;

class BalanceController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class
            ],
        ]);
    }

    public function actionIndex()
    {
        $form = new BalanceForm();
        $res = $form->getIndex();

        return $res;
    }

    public function actionLogs()
    {
        $form = new BalanceForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getLogs();

        return $res;
    }

    public function actionLogDetail()
    {
        $form = new BalanceForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getLogDetail();

        return $res;
    }
}
