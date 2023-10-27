<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/2
 * Time: 17:16
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\controllers\api;


class IndexController extends ApiController
{
    public function actions()
    {
        return [
            'setting-data' => [
                'class' => '\app\plugins\community\components\SettingDataAction'
            ]
        ];
    }
}
