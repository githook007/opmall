<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/11
 * Time: 11:50
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\controllers\mall;


use app\plugins\community\forms\mall\OrderListForm;
use app\plugins\Controller;

class OrderController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderListForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->getList());
        } else {
            return $this->render('index');
        }
    }
}
