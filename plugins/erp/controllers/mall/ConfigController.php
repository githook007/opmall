<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/8 18:11
 */

namespace app\plugins\erp\controllers\mall;

use app\plugins\Controller;
use app\plugins\erp\forms\mall\ConfigForm;

class ConfigController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new ConfigForm();
                $res = $form->getDetail();
                return $this->asJson($res);
            } else {
                $form = new ConfigForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionAuth()
    {
        $form = new ConfigForm();
        $res = $form->getAuthUrl();
        return $this->asJson($res);
    }

    public function actionShop()
    {
        $form = new ConfigForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getShop();
        return $this->asJson($res);
    }
}
