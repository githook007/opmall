<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\controllers\pc;

use app\controllers\mall\MallController;
use app\forms\pc\nav\NavForm;

class NavController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new NavForm();
            if (\Yii::$app->request->isPost) {
                $res = $form->save(\Yii::$app->request->post('form'));
                return $this->asJson($res);
            }else {
                $form->attributes = \Yii::$app->request->get();
                $list = $form->getList();
                return $this->asJson($list);
            }
        } else {
            return $this->render('index');
        }
    }


    /**
     * 删除
     * @return \yii\web\Response
     */
    public function actionDestroy()
    {
        $form = new NavForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }
}
