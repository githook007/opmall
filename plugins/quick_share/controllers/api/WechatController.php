<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\quick_share\controllers\api;


use app\controllers\api\ApiController;
use app\plugins\quick_share\forms\api\WechatForm;

class WechatController extends ApiController
{
    public function actionIndex()
    {
        $form = new WechatForm();
        $form->attributes = \Yii::$app->request->post();
        $callback = \Yii::$app->request->get('callback');
        echo $callback . '(' . \yii\helpers\BaseJson::encode($form->getInfo()) . ')';
    }

    public function actionView()
    {
        $url = dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'share.html';
        require_once($url);
    }
}