<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\admin;


use app\bootstrap\response\ApiCode;

class IndexController extends AdminController
{
    public function actionIndex()
    {
        return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin/user/me']));
    }

    public function actionBackIndex()
    {
    	\Yii::$app->removeSessionMallId();
        return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin/user/me']));
    }
}
