<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\mch;

use app\bootstrap\response\ApiCode;
use app\forms\common\mch\MchMallSettingForm;
use app\forms\common\mch\SettingForm;
use app\models\Model;

class MchSettingForm extends Model
{
    public function getSetting()
    {
        $setting = (new SettingForm())->search();
        $mchMallSetting = (new MchMallSettingForm())->search();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $setting,
                'mchMallSetting' => $mchMallSetting
            ]
        ];
    }

    public function getMchMallSetting()
    {
        $setting = (new MchMallSettingForm())->search();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'mch_mall_setting' => $setting
            ]
        ];
    }


    public function getMchSetting()
    {
        try {
            $form = new \app\forms\common\mch\MchSettingForm();
            $res = $form->search();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'setting' => $res,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}
