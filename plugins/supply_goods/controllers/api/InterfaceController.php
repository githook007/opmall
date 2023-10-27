<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\api;

use app\controllers\open_api\ApiController;
use app\plugins\supply_goods\forms\api\WholesalerStatusForm;

class InterfaceController extends ApiController
{
    public function actionMchUserStatus(){
        if(\Yii::$app->request->isPost) {
            $form = new WholesalerStatusForm();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->handleStatus());
        }
    }
}
