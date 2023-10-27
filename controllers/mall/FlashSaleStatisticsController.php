<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/5/11
 * Time: 14:48
 */

namespace app\controllers\mall;

class FlashSaleStatisticsController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $plugin = \Yii::$app->plugin->getPlugin('flash_sale');
            $form = $plugin->getApi();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->search());
        } else {
            return $this->render('index');
        }
    }
}
