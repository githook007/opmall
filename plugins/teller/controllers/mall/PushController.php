<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\teller\controllers\mall;


use app\plugins\Controller;
use app\plugins\teller\forms\mall\PushOrderForm;

class PushController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PushOrderForm();
	        $form->attributes = \Yii::$app->request->post();
	        return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }
}
