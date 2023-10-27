<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\teller\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\teller\forms\common\CommonTellerSetting;

class TellerSettingForm extends Model
{
    public function getSetting()
    {
        $setting = (new CommonTellerSetting())->search();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting,
            ]
        ];
    }
}
