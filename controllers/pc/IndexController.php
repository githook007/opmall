<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc;

use app\controllers\mall\MallController;
use app\forms\pc\IndexForm;
use app\forms\pc\SettingForm;

class IndexController extends MallController
{
    public function actionAdConfig()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new SettingForm();
            if (\Yii::$app->request->isPost) {
                $form->params = \Yii::$app->request->post();
                return $this->asJson($form->saveAdListInfo());
            }else {
                return $this->asJson($form->getAdListInfo());
            }
        }else{
            return $this->render('ad-config');
        }
    }

    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new SettingForm();
            if (\Yii::$app->request->isPost) {
                $form->params = \Yii::$app->serializer->decode(\Yii::$app->request->post("ruleForm"));
                return $this->asJson($form->saveBasicSettingInfo());
            }else {
                return $this->asJson($form->getBasicSettingInfo());
            }
        }else{
            return $this->render('setting');
        }
    }

    public function actionCat(){
        $form = new IndexForm();
        return $this->asJson($form->getCatList());
    }
}
