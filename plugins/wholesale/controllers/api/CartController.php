<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: zbj
 */

namespace app\plugins\wholesale\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\plugins\wholesale\forms\api\CartForm;

class CartController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionAddCart()
    {
        $form = new CartForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->addCart());
    }
}
