<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\controllers\api;

use app\controllers\api\ApiController;
use app\plugins\integral_mall\forms\api\coupon\CouponForm;
use app\plugins\integral_mall\forms\common\CouponListForm;

class CouponController extends ApiController
{
    public function actionIndex()
    {
        $form = new CouponListForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->search());
    }

    public function actionDetail()
    {
        $form = new CouponForm();
        $form->attributes = \Yii::$app->request->get();

        return $this->asJson($form->detail());
    }
}
