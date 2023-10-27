<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/10
 * Time: 9:20 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\controllers\api;

use app\plugins\app\forms\api\CaptchaForm;
use app\plugins\app\forms\api\EmailForm;
use app\plugins\app\forms\api\RegisterForm;

class PassportController extends ApiController
{
    public function actionRegister()
    {
        $form = new RegisterForm();
        $form->scenario = 'register';
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->register());
    }

    public function actionSmsCaptcha()
    {
        $form = new CaptchaForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->sendSmsCaptcha());
    }

    public function actionSendEmail()
    {
        $form = new EmailForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->sendEmail());
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->asJson([
            'code' => 0,
            'msg' => '退出登录成功'
        ]);
    }
}
