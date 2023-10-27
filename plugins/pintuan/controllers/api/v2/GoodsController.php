<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\controllers\api\v2;


use app\controllers\api\ApiController;
use app\plugins\pintuan\forms\api\v2\CatsForm;
use app\plugins\pintuan\forms\api\v2\GoodsForm;

class GoodsController extends ApiController
{
    public function actionIndex()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getList();

        return $this->asJson($res);
    }

    public function actionDetail()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->detail();

        return $this->asJson($res);
    }

    public function actionCats()
    {
        $form = new CatsForm();
        $res = $form->getList();

        return $this->asJson($res);
    }
}
