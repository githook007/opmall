<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\diy\controllers\mall;

use app\plugins\Controller;
use app\plugins\diy\forms\mall\TplFunc;

class TplFuncController extends Controller
{
    public function actionQuickNavGetMallConfig()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new TplFunc();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->quickNavGetMallConfig());
        }
    }
}
