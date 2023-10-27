<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;

use app\forms\mall\file\FileForm;


class FileController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new FileForm();
                $form->attributes = \Yii::$app->request->get();
                $list = $form->getList();

                return $this->asJson($list);
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * 删除全部
     * @return \yii\web\Response
     */
    public function actionDestroyAll()
    {
        $form = new FileForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->destroyAll();

        return $this->asJson($res);
    }

    /**
     * 删除全部
     * @return \yii\web\Response
     */
    public function actionDestroy()
    {
        $form = new FileForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->destroy();

        return $this->asJson($res);
    }
}
