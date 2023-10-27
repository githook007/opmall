<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\forms\mall\banner\BannerForm;

class BannerController extends MallController
{
    public function actionIndex()
    {
        $form = new BannerForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getList());
    }

    public function actionDestroy()
    {
        if ($ids = \Yii::$app->request->post('ids')) {
            $form = new BannerForm();
            $form->ids = $ids;
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
