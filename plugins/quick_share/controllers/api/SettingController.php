<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\quick_share\controllers\api;

use app\controllers\api\ApiController;
use app\bootstrap\response\ApiCode;
use app\plugins\quick_share\forms\common\CommonQuickShare;

class SettingController extends ApiController
{
    public function actionIndex()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'setting' => CommonQuickShare::getSetting(),
            ]
        ];
    }
}
