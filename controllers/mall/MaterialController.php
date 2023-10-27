<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;


class MaterialController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {

        } else {
            return $this->render('index');
        }
    }
}