<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/9/29
 * Time: 4:13 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\controllers\mall;

use app\plugins\app\forms\mall\ConfigForm;
use app\plugins\Controller;

class ConfigController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new ConfigForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }else{
                $form = new ConfigForm();
                return $this->asJson($form->get());
            }
        } else {
            return $this->render('index');
        }
    }
}
