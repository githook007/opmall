<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\controllers\pc\web;

use app\forms\pc\passport\PassportForm;
use app\forms\pc\passport\WxAppForm;

class PassportController extends CommonController
{
    /**
     * 账号密码登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $form = new PassportForm();
        $form->attributes = $this->getParams();
        $res = $form->mobileLogin();
        return $this->asJson($res);
    }

    // 注册
    public function actionRegister(){
        $form = new PassportForm();
        $form->attributes = $this->getParams();
        $res = $form->mobileRegister();
        return $this->asJson($res);
    }

    /**
     * 退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $form = new PassportForm();
        $res = $form->logout();
        return $this->asJson($res);
    }

    /**
     * 忘记密码
     * @return \yii\web\Response
     */
    public function actionForgetPassword()
    {
        $form = new PassportForm();
        $form->attributes = $this->getParams();
        $res = $form->forgetPassword();

        return $this->asJson($res);
    }

    // 微信登录
    public function actionLoginQrCode()
    {
        $form = new WxAppForm();
        $form->attributes = $this->getParams();
        $res = $form->qrCode();
        return $this->asJson($res);
    }

    // 监听微信登录结果
    public function actionListenLoginRes()
    {
        $form = new WxAppForm();
        $form->attributes = $this->getParams();
        $res = $form->listenRes();
        return $this->asJson($res);
    }
}
