<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/6/25
 * Time: 9:17
 */

namespace app\controllers\api\admin;

use app\forms\mall\postage_rules\PostageRulesListForm;

class PostageRuleController extends AdminController
{
    public function actionAllList()
    {
        $form = new PostageRulesListForm();

        return $this->asJson($form->allList());
    }
}