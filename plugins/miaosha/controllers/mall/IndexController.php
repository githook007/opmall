<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\miaosha\controllers\mall;


use app\plugins\Controller;
use app\plugins\miaosha\forms\mall\MiaoShaSettingEditForm;
use app\plugins\miaosha\forms\mall\MiaoShaSettingForm;

class IndexController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new MiaoShaSettingEditForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            } else {
                $form = new MiaoShaSettingForm();
                return $this->asJson($form->getSetting());
            }
        } else {
            return $this->render('index');
        }
    }
}
