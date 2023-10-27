<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\bargain\controllers\mall;

use app\plugins\bargain\forms\mall\BargainOrderListForm;
use app\plugins\Controller;

class InfoController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new BargainOrderListForm();
                $form->attributes = \Yii::$app->request->get();
                $form->mall = \Yii::$app->mall;
                return $this->asJson($form->search());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionSingle()
    {
        return $this->render('single');
    }
}
