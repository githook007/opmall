<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/8/13
 * Time: 14:16
 */

namespace app\plugins\ttapp\controllers;

use app\plugins\ttapp\forms\TemplateMsgEditForm;
use app\plugins\ttapp\forms\TemplateMsgForm;
use app\plugins\Controller;

class TemplateMsgController extends Controller
{
    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new TemplateMsgForm();
                $res = $form->getDetail();

                return $this->asJson($res);
            } else {
                $form = new TemplateMsgEditForm();
                $form->data = \Yii::$app->request->post('list');
                return $form->save();
            }
        } else {
            return $this->render('setting');
        }
    }

    public function actionAddTemplate()
    {
        $form = new TemplateMsgForm();
        $form->mall = \Yii::$app->mall;
        return $this->asJson($form->search());
    }
}
