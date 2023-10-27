<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\mall;

use app\plugins\Controller;
use app\plugins\supply_goods\forms\mall\WholesalerForm;
use app\plugins\supply_goods\forms\mall\WholesalerSubmitForm;

class WholesalerController extends Controller
{
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost){
                $form = new WholesalerSubmitForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
        } else {
            return $this->render('edit');
        }
    }

    public function actionIndex(){
        if (\Yii::$app->request->isAjax) {
            $form = new WholesalerForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->getInfo());
        } else {
            return $this->render('index');
        }
    }
}
