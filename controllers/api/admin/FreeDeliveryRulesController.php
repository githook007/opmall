<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/9/23
 * Time: 15:40
 */

namespace app\controllers\api\admin;

use app\forms\mall\free_delivery_rules\ListForm;

class FreeDeliveryRulesController extends AdminController
{
    public function actionAllList()
    {
        $form = new ListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->allList());
    }
}
