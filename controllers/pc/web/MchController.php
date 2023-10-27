<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\controllers\pc\web;

use app\controllers\pc\web\filters\LoginFilter;
use app\forms\pc\mch\GoodsForm;
use app\forms\pc\mch\MchEditForm;
use app\forms\pc\mch\MchForm;

class MchController extends CommonController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => ['index', 'detail','setting', 'goods-list']
            ],
        ]);
    }

    public function actionDetail()
    {
        $form = new MchForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->getDetail());
    }

    public function actionSetting()
    {
        $form = new MchForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->setting());
    }

    public function actionApply()
    {
        $form = new MchEditForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->save());
    }

    public function actionGoodsList()
    {
        $form = new GoodsForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->getList());
    }
}
