<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/14 16:01
 */


namespace app\plugins\scan_code_pay\controllers\api;


use app\controllers\api\ApiController;
use app\controllers\api\filters\LoginFilter;
use app\plugins\scan_code_pay\forms\api\CouponsForm;
use app\plugins\scan_code_pay\forms\api\OrderForm;
use app\plugins\scan_code_pay\forms\api\OrderSubmitForm;

class OrderController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionPreview()
    {
        $form = new OrderSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->preview());
    }

    public function actionSubmit()
    {
        $form = new OrderSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->submit());
    }

    public function actionCoupons()
    {
        $form = new CouponsForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->getCoupons());
    }

    public function actionCancel()
    {
        $form = new OrderForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->orderCancel());
    }
}
