<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/5/29
 * Time: 13:41
 */

namespace app\controllers\api\admin;

use app\forms\mall\service\ServiceForm;

class ServiceController extends AdminController
{
    /**
     * 获取商品服务列表
     * @return \yii\web\Response
     */
    public function actionOptions()
    {
        $form = new ServiceForm();
        $res = $form->getOptionList();

        return $this->asJson($res);
    }
}