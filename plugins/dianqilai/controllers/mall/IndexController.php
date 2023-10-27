<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/3
 * Time: 11:45
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\dianqilai\controllers\mall;


use app\plugins\Controller;
use app\plugins\dianqilai\forms\IndexForm;

class IndexController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new IndexForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->search());
            }
        } else {
            return $this->render('index');
        }
    }
}
