<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\api;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\exchange\forms\common\CommonSetting;

class SettingForm extends Model
{
    public function get()
    {
        $commonSetting = new CommonSetting();
        $setting = $commonSetting->get();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $setting,
        ];
    }
}