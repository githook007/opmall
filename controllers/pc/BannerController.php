<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc;

use app\controllers\mall\MallController;
use app\forms\pc\banner\BannerForm;

class BannerController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new BannerForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getList());
        }else{
            return $this->render('index');
        }
    }

    public function actionDestroy()
    {
        if ($id = \Yii::$app->request->get('id')) {
            $form = new BannerForm();
            $form->id = $id;
            return $this->asJson($form->destroy());
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new BannerForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            } else {
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('edit');
        }
    }
}
