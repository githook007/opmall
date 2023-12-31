<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * User: jack_guo
 * Date: 2019/7/3
 * Time: 16:18
 */
namespace app\plugins\stock\controllers\mall;

use app\plugins\stock\models\TemplateForm;
use app\plugins\stock\forms\mall\SettingForm;
use app\plugins\Controller;

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new SettingForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            } else {
                $form = new SettingForm();
                return $this->asJson($form->search());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionTemplate()
    {
//        if (\Yii::$app->request->isAjax) {
//            if (\Yii::$app->request->isGet) {
//                $form = new TemplateForm();
//                $form->mall = \Yii::$app->mall;
//                $add = \Yii::$app->request->get('add');
//                return $this->asJson($form->getDetail($add));
//            }
//            if (\Yii::$app->request->isPost) {
//                $form = new TemplateForm();
//                $form->attributes = \Yii::$app->request->post();
//                $form->mall = \Yii::$app->mall;
//                return $this->asJson($form->save());
//            }
//        }
//        return $this->render('template');
    }
}