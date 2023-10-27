<?php
/**
 * Created By PhpStorm
 * Date: 2021/7/3
 * Time: 10:05 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\controllers\mall\v2;

use app\controllers\mall\MallController;
use app\forms\mall\user_center\v2\UserCenterEditForm;

class UserCenterController extends MallController
{
    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new UserCenterEditForm();
            if (\Yii::$app->request->isPost) {
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            } else {
                return $this->asJson(\Yii::$app->str2url($form->get()));
            }
        } else {
            return $this->render('detail');
        }
    }
}
