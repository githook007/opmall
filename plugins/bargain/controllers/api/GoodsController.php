<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/14
 * Time: 9:14
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\controllers\api;


use app\controllers\api\ApiController;
use app\controllers\api\filters\LoginFilter;
use app\plugins\bargain\forms\api\GoodsForm;
use app\plugins\bargain\forms\api\GoodsListForm;

class GoodsController extends ApiController
{
    public function actionList()
    {
        $form = new GoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mall = \Yii::$app->mall;
        return $this->asJson($form->search());
    }

    public function actionDetail()
    {
        $form = new GoodsForm();
        $form->attributes = \Yii::$app->request->get();
        $form->user = \Yii::$app->user->identity;
        $form->mall = \Yii::$app->mall;
        return $this->asJson($form->search());
    }
}
