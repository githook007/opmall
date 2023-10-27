<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\mall;

use app\plugins\Controller;
use app\plugins\supply_goods\forms\mall\MchGoodsForm;
use app\plugins\supply_goods\forms\mall\MchGoodsListForm;

class MchGoodsController extends Controller
{
    public function actionMchGoodsList()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet){
                $form = new MchGoodsListForm();
                $form->attributes = \Yii::$app->request->get();
                $form->attributes = \Yii::$app->request->get('search');
                return $this->asJson($form->getList());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionGoodsDetail(){
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet){
                $form = new MchGoodsForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('detail');
        }
    }
}
