<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\recharge;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\models\Model;

class RechargeSettingForm extends Model
{
    public function rules()
    {
        return [];
    }

    public function getIndex()
    {
        $setting = CommonAppConfig::getRechargeSetting();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting
            ]
        ];
    }
}
