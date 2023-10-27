<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc\web;

use app\forms\pc\HomeForm;
use app\forms\pc\IndexForm;
use app\forms\pc\user\SmsForm;
use app\models\DistrictArr;

class IndexController extends CommonController
{
    public function actionTest(){
//        echo "<pre>";print_r(\Yii::$app->security->validatePassword("123456", '$2y$13$VbH7GQiVOFCTff.EU4EzG.yN4TXbR2H1eXNw46uyLP/sPci.Y5Vfm'));die("");
    }

    // 发送短信验证码
    public function actionSmsCode()
    {
        $form = new SmsForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->code());
    }

    public function actionGlobalData(){
        $form = new HomeForm();
        return $this->asJson($form->getGlobalData());
    }

    public function actionHomeData(){
        $form = new HomeForm();
        return $this->asJson($form->getHomeData());
    }

    public function actionQrCode(){
        $url = urldecode($this->getParams("url", ""));
        \QRcode::png($url);
    }

    public function actionGetAddress()
    {
        $arr = DistrictArr::getArr();
        foreach ($arr as &$item){
            $item['id'] = strval($item['id']);
        }
        unset($item);
        return $this->asJson(["code" => 0, "data" => IndexForm::iterationTree($arr, "id", "parent_id", 1)]);
    }
}
