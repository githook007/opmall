<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\forms\mall\page\PageIntroEditForm;
use app\forms\mall\page\PageIntroForm;

class PageController extends MallController
{
    public function actionIntro()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PageIntroEditForm();
                $form->attributes = \Yii::$app->request->post();
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new PageIntroForm();
                $form->attributes = \Yii::$app->request->get();
                $detail = $form->getDetail();

                return $this->asJson($detail);
            }
        }
    }

    public function actionUpdate(){
        $form = new PageIntroForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->update();
    }
}
