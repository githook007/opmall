<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\controllers\mall;


use app\plugins\Controller;
use app\plugins\pintuan\forms\mall\RobotEditForm;
use app\plugins\pintuan\forms\mall\RobotForm;

class RobotController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new RobotForm();
            $form->attributes = \Yii::$app->request->get();
            $res = $form->getList();;

            return $this->asJson($res);
        } else {
            return $this->render('index');
        }
    }

    public function actionEdit(){
        if (\Yii::$app->request->isPost) {
            $form = new RobotEditForm();
            $form->attributes = \Yii::$app->request->post();
            $res = $form->save();

            return $this->asJson($res);
        } else {

        }
    }

    public function actionDestroy()
    {
        $form = new RobotForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }
}