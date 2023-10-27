<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/11/4
 * Time: 16:14
 */

namespace app\controllers\mall;

use app\forms\mall\pay_type_setting\PayTypeSettingForm;

class PayTypeSettingController extends MallController
{
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PayTypeSettingForm();
                $form->attributes = \Yii::$app->request->post();
                $res = $form->save();
                return $this->asJson($res);
            } else {
                $form = new PayTypeSettingForm();
                $form->attributes = \Yii::$app->request->get();
                $detail = $form->getDetail();
                return $this->asJson($detail);
            }
        } else {
            return $this->render('edit');
        }
    }
}
