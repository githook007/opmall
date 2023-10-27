<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\controllers\api;

use app\controllers\api\ApiController;
use app\plugins\integral_mall\forms\api\CatsForm;
use app\plugins\integral_mall\forms\api\GoodsForm;

class GoodsController extends ApiController
{
    public function actionIndex()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->getList());
    }

    public function actionDetail()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->detail());
    }

    public function actionCats()
    {
        $form = new CatsForm();
        $res = $form->getList();

        return $this->asJson($res);
    }
}
