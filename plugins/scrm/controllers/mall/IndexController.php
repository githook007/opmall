<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 1:45 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\controllers\mall;

use app\plugins\scrm\forms\mall\ConfigForm;
use app\plugins\scrm\forms\mall\IndexForm;

class IndexController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new IndexForm();
            if (\Yii::$app->request->isPost) {
                return $this->asJson($form->reset());
            } else {
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionSubmit()
    {
        $form = new ConfigForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }
}
