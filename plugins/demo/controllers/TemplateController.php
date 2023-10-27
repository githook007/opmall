<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/3
 * Time: 10:13
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\demo\controllers;


use app\plugins\Controller;
use app\plugins\demo\models\TemplateForm;

class TemplateController extends Controller
{
    public function actionTemplate()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new TemplateForm();
                $form->mall = \Yii::$app->mall;
                $add = \Yii::$app->request->get('add');
                return $this->asJson($form->getDetail($add));
            }
            if (\Yii::$app->request->isPost) {
                $form = new TemplateForm();
                $form->attributes = \Yii::$app->request->post();
                $form->mall = \Yii::$app->mall;
                return $this->asJson($form->save());
            }
        }
        return $this->render('template');
    }
}
