<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\mall;

use app\plugins\Controller;
use app\plugins\shopping\forms\mall\BuyOrderEditForm;
use app\plugins\shopping\forms\mall\BuyOrderForm;

class BuyOrderController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new BuyOrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->keyword = \Yii::$app->serializer->decode(\Yii::$app->request->get('search'))['keyword'];
            return $this->asJson($form->getAddList());
        } else {
            return $this->render('index');
        }
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new BuyOrderEditForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->add());
            } else {
                $form = new BuyOrderForm();
                $form->attributes = \Yii::$app->request->get();
                $form->keyword = \Yii::$app->serializer->decode(\Yii::$app->request->get('search'))['keyword'];
                return $this->asJson($form->getList());
            }
        } else {
            return $this->render('edit');
        }
    }

    //订单详情
    public function actionDestroy()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new BuyOrderForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->destroy();
        }
    }
}
