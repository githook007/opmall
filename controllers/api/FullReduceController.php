<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/7/16
 * Time: 11:53
 */

namespace app\controllers\api;

use app\forms\api\full_reduce\ActivityForm;

class FullReduceController extends ApiController
{
    public function actionIndex()
    {
        $form = new ActivityForm();
        return $this->asJson($form->getActivity());
    }
}
