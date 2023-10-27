<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\controllers\mall;


use app\forms\mall\app_page\AppPageForm;
use app\forms\PickLinkForm;

class AppPageController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new PickLinkForm();
                $form->only = $form::ONLY_PAGE;
                $form->ignore = $form::ONLY_PAGE;
                $res = $form->appPage();
                return $this->asJson($res);
            } else {
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionQrcode()
    {
        $form = new AppPageForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->search());
    }
}
