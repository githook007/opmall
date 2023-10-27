<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\controllers\mall;


use app\forms\mall\home_page\HomePageEditForm;
use app\forms\mall\home_page\HomePageForm;

class HomePageController extends MallController
{
    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new HomePageForm();
                $res = $form->getDetail();

                return $this->asJson($res);
            } else {
                $form = new HomePageEditForm();
                $form->data = \Yii::$app->request->post('list');
                return $form->save();
            }
        } else {
            return $this->render('setting');
        }
    }

    public function actionOption()
    {
        $form = new HomePageForm();
        $res = $form->getOption();

        return $this->asJson($res);
    }
}
