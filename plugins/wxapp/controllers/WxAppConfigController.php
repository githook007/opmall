<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\wxapp\controllers;

use app\plugins\Controller;
use app\plugins\wxapp\forms\wx_app_config\PemUploadForm;
use app\plugins\wxapp\forms\wx_app_config\PrivacyEditForm;
use app\plugins\wxapp\forms\wx_app_config\WxAppConfigEditForm;
use app\plugins\wxapp\forms\wx_app_config\WxAppConfigForm;
use yii\web\UploadedFile;

class WxAppConfigController extends Controller
{
    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new WxAppConfigForm();
                $res = $form->getDetail();

                return $this->asJson($res);
            } else {
                $form = new WxAppConfigEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            }
        } else {
            return $this->render('setting');
        }
    }

    // @czs
    public function actionPrivacySetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new WxAppConfigForm();
                $res = $form->getPrivacyInfo();

                return $this->asJson($res);
            } else {
                $form = new PrivacyEditForm();
                $form->data = \Yii::$app->request->post('ruleForm');
                $form->other = \Yii::$app->request->post('other');
                return $form->savePrivacyInfo();
            }
        } else {
            return $this->render('privacy-setting');
        }
    }

//    public function actionUploadPem($name = 'file')
//    {
//        $form = new PemUploadForm();
//        $form->file = UploadedFile::getInstanceByName($name);
//        $form->id = \Yii::$app->request->get('id');
//        $form->type = \Yii::$app->request->get('type');
//        return $form->save();
//    }
}
