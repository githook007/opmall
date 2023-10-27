<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\diy\controllers\api;


use app\controllers\api\ApiController;
use app\controllers\behaviors\LoginFilter;
use app\plugins\diy\forms\api\UserCouponForm;


class CouponController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionReceive()
    {
        $form = new UserCouponForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->receive());
    }
}