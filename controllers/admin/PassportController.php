<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\admin;


use app\bootstrap\response\ApiCode;
use app\forms\admin\passport\MchPassportForm;
use app\forms\admin\passport\MchQrCodePassportForm;
use app\forms\admin\passport\MchSettingForm;
use app\forms\admin\passport\PassportForm;
use app\forms\admin\passport\RegisterForm;
use app\forms\admin\passport\ResetPasswordForm;
use app\forms\admin\passport\SendRestPasswordCaptchaForm;
use app\forms\admin\SmsCaptchaForm;
use app\forms\common\CommonOption;
use app\models\AdminRegister;
use app\models\Option;
use app\models\User;

class PassportController extends AdminController
{
    public $layout = 'main';

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $baseName = pathinfo(\Yii::$app->request->scriptUrl, PATHINFO_BASENAME);
        if($baseName == "index.php"){
            return $this->redirect(\Yii::$app->request->baseUrl . "/404.php");
        }
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PassportForm();
                $form->attributes = \Yii::$app->request->post('form');
                $form->user_type = \Yii::$app->request->post('user_type');
                $form->mall_id = \Yii::$app->request->post('mall_id');
                $res = $form->login();

                return $this->asJson($res);
            }
        } else {
            return $this->render('login', ["key" => PassportForm::DES_KEY]); // @czs
        }
    }

    /**
     * 独立版总后台 账号注销
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $logout = \Yii::$app->user->logout();

        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'url' => 'admin/passport/login'
            ]
        ]);
    }

    /**
     * 注册申请
     * @return string
     */
    public function actionRegister()
    {
        $status = \Yii::$app->request->get('status');
        $indSetting = CommonOption::get(Option::NAME_IND_SETTING);
        if (!$indSetting || !isset($indSetting['open_register']) || $indSetting['open_register'] != 1
            && $status != 'forget') {
            return $this->goBack();
        }
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new RegisterForm();
                $form->attributes = \Yii::$app->request->post('form');
                $res = $form->register();

                return $this->asJson($res);
            }
        } else {
            return $this->render('register');
        }
    }

    public function actionSmsCaptcha()
    {
        $form = new SmsCaptchaForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->send());
    }

    public function actionCheckUserExists()
    {
        $username = \Yii::$app->request->post('username');
        $exists = User::find()->where([
            'username' => $username,
            'is_delete' => 0,
            'mch_id' => 0,
            'mall_id' => 0,
        ])->exists();
        if ($exists) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'is_exists' => true,
                ],
            ];
        }
        $exists = AdminRegister::find()->where([
            'username' => $username,
            'status' => 0,
            'is_delete' => 0,
        ])->exists();
        if ($exists) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'is_exists' => true,
                ],
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'is_exists' => false,
            ],
        ];
    }

    public function actionSendResetPasswordCaptcha()
    {
        $form = new SendRestPasswordCaptchaForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->send();
    }

    public function actionResetPassword()
    {
        $form = new ResetPasswordForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }

    /**
     * 多商户登录
     * @return string|\yii\web\Response
     */
    public function actionMchLogin()
    {
        $baseName = pathinfo(\Yii::$app->request->scriptUrl, PATHINFO_BASENAME);
        if($baseName == "index.php"){
            return $this->redirect(\Yii::$app->request->baseUrl . "/404.php");
        }
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new MchPassportForm();
                $form->attributes = \Yii::$app->request->post('form');
                $res = $form->login();

                return $this->asJson($res);
            }
        } else {
            return $this->render('mch-login', ["key" => MchPassportForm::DES_KEY]); // @czs
        }
    }

    public function actionLoginQrCode()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MchQrCodePassportForm();
            $form->mall_id = \Yii::$app->request->post('mall_id');
            $res = $form->getLoginQrCode();

            return $this->asJson($res);
        }
    }

    public function actionCheckMchLogin()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MchQrCodePassportForm();
            $form->token = \Yii::$app->request->post('token');
            $form->mall_id = \Yii::$app->request->post('mall_id');
            $res = $form->checkMchLogin();

            return $this->asJson($res);
        }
    }

    public function actionMchSetting()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new MchSettingForm();
            $form->mall_id = \Yii::$app->request->get('mall_id');
            $res = $form->getMchSetting();

            return $this->asJson($res);
        }
    }

    public function actionRoleSetting()
    {
        $form = new PassportForm();
        $res = $form->getRoleSetting();

        return $this->asJson($res);
    }

    public function actionCashLogin()
    {
        return $this->render('cash-login');
    }

}
