<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\mall;

use app\forms\mall\goods\GoodsEditForm;
use app\forms\mall\goods\GoodsForm;
use app\plugins\Controller;
use app\plugins\supply_goods\forms\mall\GoodsSaveForm;
use app\plugins\supply_goods\forms\mall\GoodsListForm;

class GoodsController extends Controller
{
    // 保存成我的货源
    public function actionSave(){
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            $form = new GoodsSaveForm();
            $form->attributes = json_decode($data['form'], true);
            $form->attrGroups = json_decode($data['attrGroups'], true);
            $res = $form->save();
            return $this->asJson($res);
        }
    }

    // 操作我的货源
    public function actionEdit(){
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $data = \Yii::$app->request->post();
                $form = new GoodsEditForm();
                $form->attributes = json_decode($data['form'], true);
                $form->attrGroups = json_decode($data['attrGroups'], true);
                $res = $form->save();
                return $this->asJson($res);
            }else{
                $form = new GoodsForm();
                $form->attributes = \Yii::$app->request->get();
                $res = $form->getDetail();
                return $this->asJson($res);
            }
        } else {
            return $this->render('edit');
        }
    }

    public function actionIndex(){
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsListForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->get('search');
            $res = $form->getList();
            return $this->asJson($res);
        } else {
            return $this->render('index');
        }
    }
}
