<?php

namespace app\plugins\scrm\controllers\api;

use app\plugins\scrm\filter\LoginFilter;
use app\plugins\scrm\forms\api\ExchangeForm;

class ExchangeController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionList()
    {
        $form = new ExchangeForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getList());
    }

    public function actionAdd()
    {
        $form = new ExchangeForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->add());
    }
}
