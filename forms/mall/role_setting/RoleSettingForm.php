<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\role_setting;


use app\bootstrap\response\ApiCode;
use app\models\Model;

class RoleSettingForm extends Model
{
    public function getDetail()
    {
        $form = new \app\forms\common\RoleSettingForm();
        $setting = $form->getSetting();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting,
            ]
        ];
    }
}
