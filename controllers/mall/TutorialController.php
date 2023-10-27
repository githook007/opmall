<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c) 2018 hook007
* author: opmall
*/

namespace app\controllers\mall;

use app\models\UserIdentity;
use app\forms\mall\tutorial\TutorialSettingForm;

class TutorialController extends MallController
{
    public function actionIndex()
    {
        $user = UserIdentity::findOne([
            'user_id' => \Yii::$app->user->id
        ]);
  
        $form = new TutorialSettingForm();
        $form->attributes = \Yii::$app->request->get();
        $info = $form->get();

        if ($info['data']['status'] == 0 && !$user->is_super_admin) {
            $url = \Yii::$app->urlManager->createUrl(['mall/index']);
            return $this->redirect($url)->send();
        }
        if (\Yii::$app->request->isAjax) {
            return $this->asJson($info);
        } else {
            return $this->render('index');
        }
    }

    public function actionSetting()
    {
        $user = UserIdentity::findOne([
            'user_id' => \Yii::$app->user->id
        ]);

        if (!$user->is_super_admin) {
            $url = \Yii::$app->urlManager->createUrl(['mall/index']);
            return $this->redirect($url)->send();
        }

        if (\Yii::$app->request->isAjax) {
            $form = new TutorialSettingForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $form->set();
            } else {
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->get());
            }
        } else {
            return $this->render('setting');
        }
    }
}
