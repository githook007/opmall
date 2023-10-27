<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\plugins\teller\controllers\api;

use app\controllers\api\ApiController;
use app\plugins\teller\forms\api\MemberQrCodeForm;
use app\controllers\api\filters\LoginFilter;

class MemberController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionPayCode()
    {
        $form = new MemberQrCodeForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->search();

        return $this->asJson($res);
    }

    public function actionSearchPayCode()
    {
        $form = new MemberQrCodeForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->searchPayCode();

        return $this->asJson($res);
    }
}
