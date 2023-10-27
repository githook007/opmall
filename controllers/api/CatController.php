<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2019 hook007
 * author: opmall
 */

namespace app\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\forms\api\cat\CatsForm;
use app\forms\api\cat\GoodsListForm;

class CatController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => ['list', 'goods']
            ],
        ]);
    }

    public function actionList()
    {
        $form = new CatsForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->search();
    }

    public function actionGoods()
    {
        $form = new GoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->getCatGoodsList();
    }
}