<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 9:10 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\controllers\mall;

use app\plugins\minishop\filter\WxappFilter;
use app\plugins\minishop\forms\cat\CatForm;
use app\plugins\minishop\forms\cat\EditCatForm;
use app\plugins\minishop\forms\cat\OperateForm;

class CatController extends MallController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'wxappFilter' => [
                'class' => WxappFilter::class,
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new CatForm();
                $form->attributes = \Yii::$app->request->get();
                return $this->asJson($form->get());
            } else {
                $form = new EditCatForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionCat()
    {
        $form = new CatForm();
        return $this->asJson($form->getCat());
    }

    public function actionOperate()
    {
        $form = new OperateForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->execute());
    }
}
