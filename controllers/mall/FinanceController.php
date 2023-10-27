<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\finance\CashApplyForm;
use app\forms\mall\finance\FinanceForm;

class FinanceController extends MallController
{
    public function actionCash()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new FinanceForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson([
                'code' => 0,
                'data' => $form->search()
            ]);
        } else {
            return $this->render('cash');   
        }
    }

    public function actionCashApply()
    {
        $form = new CashApplyForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    public function actionRemark()
    {
        $form = new CashApplyForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->remark());
    }

    public function actionPermission()
    {
        $form = new FinanceForm();
        $form->attributes = \Yii::$app->request->get();
        $list = $form->getPermission();
        $newList = [];
        foreach ($list as $item) {
            $newItem = [];
            $newItem['name'] = $item['name'];
            $newItem['key'] = $item['key'];
            $newList[] = $newItem;
        }
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => $newList
        ]);
    }
}
