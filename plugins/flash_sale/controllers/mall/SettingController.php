<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/4/30
 * Time: 15:13
 */

namespace app\plugins\flash_sale\controllers\mall;

use app\plugins\Controller;
use app\plugins\flash_sale\forms\common\CommonSetting;
use app\plugins\flash_sale\forms\mall\SettingForm;
use Yii;

class SettingController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isPost) {
                $form = new SettingForm();
                $form->attributes = Yii::$app->request->post();
                return $this->asJson($form->save());
            } else {
                $form = new CommonSetting();
                return $this->asJson(
                    [
                        'code' => 0,
                        'data' => $form->search()
                    ]
                );
            }
        } else {
            return $this->render('index');
        }
    }
}
