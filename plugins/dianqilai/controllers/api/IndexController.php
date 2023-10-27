<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/3
 * Time: 16:17
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\dianqilai\controllers\api;


use app\controllers\Controller;
use app\plugins\dianqilai\forms\CallbackForm;

class IndexController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $form = new CallbackForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->search());
    }
}
