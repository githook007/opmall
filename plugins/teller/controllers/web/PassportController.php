<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\teller\controllers\web;

use app\bootstrap\response\ApiCode;
use app\plugins\teller\controllers\web\WebController;
use app\plugins\teller\forms\web\LoginSettingForm;
use app\plugins\teller\forms\web\TellerPassportForm;

class PassportController extends WebController
{    
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
                $form = new TellerPassportForm();
                $form->attributes = \Yii::$app->request->post();
                $res = $form->login();

                return $this->asJson($res);
            }
        } else {
            return $this->render('login');
        }
    }

    /**
     * 登录页数据
     * @return \yii\web\Response
     */
    public function actionSetting()
    {
        $form = new LoginSettingForm();
        $form->attributes = \Yii::$app->request->get();

        $setting = $form->search();

        return $this->asJson($setting);
    }

    /**
     * 注销
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $mallId = base64_encode(\Yii::$app->mall->id);
        $logout = \Yii::$app->user->logout();

        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'url' => 'plugin/teller/web/passport/login',
                'mall_id' => $mallId
            ]
        ]);
    }
}
