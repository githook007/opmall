<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2019/2/26
 * Time: 10:55
 */

namespace app\plugins\wxapp\controllers;

use app\plugins\Controller;
use app\plugins\wxapp\forms\AppPluginsForm;
use app\plugins\wxapp\forms\AppQrcodeForm;
use app\plugins\wxapp\forms\AppUploadForm;

class AppUploadController extends Controller
{
    public function actionIndex($branch = null)
    {
        if (\Yii::$app->request->isAjax) {
            $form = new AppUploadForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getResponse());
        } else {
            return $this->render('index', [
                'branch' => $branch,
            ]);
        }
    }

    public function actionGetInvokeCode(){
        $form = new AppPluginsForm();
        $form->attributes = \Yii::$app->request->post();
        $form->save();
        $form = new AppUploadForm();
        return $this->asJson($form->getInvokeCode());
    }

    public function actionNoMch()
    {
        return $this->actionIndex('nomch');
    }

    public function actionAppQrcode()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new AppQrcodeForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getResponse());
        }
    }

    public function actionAppPlugins()
    {
        $form = new AppPluginsForm();
        $data = $form->search();
        $form = new AppUploadForm();
        $data['ip'] = $form->getIp();
        return $data;
    }
}