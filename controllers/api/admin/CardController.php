<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/5/29
 * Time: 13:48
 */

namespace app\controllers\api\admin;

use app\forms\mall\card\CardForm;

class CardController extends AdminController
{
    /**
     * 获取商品卡券列表
     * @return \yii\web\Response
     */
    public function actionOptions()
    {
        $form = new CardForm();
        $res = $form->getOptionList();

        return $this->asJson($res);
    }
}