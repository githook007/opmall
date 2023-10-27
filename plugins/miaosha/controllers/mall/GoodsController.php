<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\controllers\mall;


use app\plugins\Controller;
use app\plugins\miaosha\forms\mall\GoodsEditForm;
use app\plugins\miaosha\forms\mall\GoodsForm;
use app\plugins\miaosha\forms\mall\GoodsListForm;

class GoodsController extends Controller
{
    public function actionIndex()
    {
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

    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new GoodsForm();
                $form->attributes = \Yii::$app->request->get();
                $res = $form->getDetail();

                return $this->asJson($res);
            }
            if (\Yii::$app->request->isPost) {
                $data = \Yii::$app->request->post();
                $form = new GoodsEditForm();
                $form->attributes = json_decode($data['form'], true);
                $form->attributes = json_decode($data['form'], true)['detail'];
                $form->attrGroups = json_decode($data['attrGroups'], true);
                $res = $form->save();

                return $this->asJson($res);
            }
        } else {
            return $this->render('edit');
        }
    }

    public function actionMiaoshaList()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsForm();
            $form->attributes = \Yii::$app->request->get();
            $res = $form->getMiaoshaList();

            return $this->asJson($res);
        } else {
            return $this->render('miaosha-list');
        }
    }

    /**
     * 批量删除秒杀商品
     * @return \yii\web\Response
     */
    public function actionBatchDestroy()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsForm();
            $form->attributes = \Yii::$app->request->post();
            $res = $form->batchDestroy();

            return $this->asJson($res);
        }
    }

    /**
     * 批量删除秒杀场次
     * @return \yii\web\Response
     */
    public function actionBatchMiaoshaDestroy()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsForm();
            $form->attributes = \Yii::$app->request->post();
            $res = $form->batchMiaoshaDestroy();

            return $this->asJson($res);
        }
    }

    /**
     * 批量更新秒杀商品状态
     * @return \yii\web\Response
     */
    public function actionBatchUpdateStatus()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new GoodsForm();
            $form->attributes = \Yii::$app->request->post();
            $res = $form->batchUpdateStatus();

            return $this->asJson($res);
        }
    }

    // 批量设置运费
    public function actionBatchUpdateFreight()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->batchUpdateFreight();

        return $this->asJson($res);
    }
    // 批量设置限购
    public function actionBatchUpdateConfineCount()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->batchUpdateConfineCount();

        return $this->asJson($res);
    }
}
