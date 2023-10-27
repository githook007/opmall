<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 16:10
 */

namespace app\controllers;

use app\forms\BdCaptchaAction;
use app\forms\WxServerForm;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'pic-captcha' => [
                'class' => BdCaptchaAction::class,
                'minLength' => 4,
                'maxLength' => 5,
                'padding' => 5,
                'offset' => 4,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['admin/index/index']));
    }

    public function actionInstallPlugin($name)
    {
        var_dump(\Yii::$app->plugin->install($name));
    }

    public function actionScheme($id)
    {
        $plugin = \Yii::$app->plugin->getPlugin("url_scheme");
        try{
            $url = $plugin->getSchemeUrl($id);
        }catch (\Exception $e){
            $url = '';
        }

        return $this->render('scheme', [
            'url' => $url
        ]);
    }

    public function actionTrialApp(){
        $form = new WxServerForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->getTrialApp();
    }
}
