<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\controllers\api\v2;


use app\controllers\api\ApiController;
use app\controllers\api\filters\LoginFilter;
use app\plugins\miaosha\forms\api\v2\poster\PosterNewForm;
use app\plugins\miaosha\forms\api\v2\poster\PosterConfigForm;


class PosterController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => []
            ],
        ]);
    }

    public function actionConfig()
    {
        $form = new PosterConfigForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getDetail());
    }

    public function actionGenerate()
    {
        $form = new PosterNewForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->poster());
    }
}