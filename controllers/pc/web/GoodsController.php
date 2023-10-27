<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc\web;

use app\forms\pc\goods\CommentsForm;
use app\forms\pc\goods\GoodsForm;
use app\forms\pc\goods\GoodsListForm;

class GoodsController extends CommonController
{
    // 商品列表页
    public function actionList()
    {
        $form = new GoodsListForm();
        $form->attributes = $this->getParams();
        return $form->search();
    }

    // 商品详情
    public function actionDetail()
    {
        $form = new GoodsForm();
        $form->id = $this->getParams("id");
        $form->mch_id = $this->getParams("mch_id", 0);
        return $this->asJson($form->getDetail());
    }

    // 评论列表
    public function actionCommentsList()
    {
        $form = new CommentsForm();
        $form->attributes = $this->getParams();
        $form->mall = \Yii::$app->mall;
        return $this->asJson($form->search());
    }
}
