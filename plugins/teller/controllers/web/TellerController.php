<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 18:18
 */


namespace app\plugins\teller\controllers\web;

use app\plugins\teller\controllers\web\filter\TellerPermissionsBehavior;

class TellerController extends WebController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'permissions' => [
                'class' => TellerPermissionsBehavior::class,
            ],
        ]);
    }
}
