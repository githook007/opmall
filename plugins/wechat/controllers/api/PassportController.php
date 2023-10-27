<?php
/**
 * Created by PhpStorm
 * Date: 2020/10/21
 * Time: 3:31 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\wechat\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\plugins\wechat\forms\api\WechatForm;

class PassportController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'only' => ['update']
            ],
        ]);
    }

    public function actionCheck()
    {
        $form = new WechatForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->result());
    }

    public function actionLoginUrl()
    {
        $form = new WechatForm();
        $form->attributes = \Yii::$app->request->get();
        return $form->loginUrl();
    }

    public function actionUpdate()
    {
        $form = new WechatForm();
        return $this->asJson($form->updateSubscribe());
    }
}
