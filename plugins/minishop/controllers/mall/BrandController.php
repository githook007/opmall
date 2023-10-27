<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/25
 * Time: 8:19 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\controllers\mall;

use app\plugins\minishop\forms\Brand\BrandEditForm;
use app\plugins\minishop\forms\Brand\BrandForm;
use app\plugins\minishop\forms\Brand\OperateForm;

class BrandController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new BrandForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->getList());
            } else {
                $form = new BrandEditForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionOperate()
    {
        $form = new OperateForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->execute());
    }
}
