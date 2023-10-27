<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\teller\controllers\web;


use app\plugins\Controller;
use app\plugins\teller\forms\web\MemberCouponForm;
use app\plugins\teller\forms\web\MemberForm;
use app\plugins\teller\forms\web\RechargeForm;
use app\plugins\teller\forms\web\TellerSetPayPasswordForm;
use app\plugins\teller\forms\web\TellerVerifyPayPasswordForm;
use app\plugins\teller\forms\web\order\TellerOrderSubmitForm;

class MemberController extends TellerController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MemberForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->search());
        } else {
            return $this->render('index');
        }
    }

    // 充值方案列表
    public function actionRechargeList()
    {
    	$form = new RechargeForm();
        $res = $form->search();

        return $this->asJson($res);
    }

    // 设置支付密码
    public function actionSetPayPassword()
    {
        $form = new TellerSetPayPasswordForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->save();

        return $this->asJson($res);
    }

    // 验证支付密码
    public function actionVerifyPayPassword()
    {
        $form = new TellerVerifyPayPasswordForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->verify();

        return $this->asJson($res);
    }

    // 获取付款码
    public function actionPayCode()
    {
        $form = new MemberQrCodeForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->search();

        return $this->asJson($res);
    }
}
