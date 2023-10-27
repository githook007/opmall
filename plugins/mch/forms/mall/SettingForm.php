<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\common\mch\MchSettingForm;
use app\models\Model;

class SettingForm extends Model
{
    public function getSetting()
    {
        try {
            $form = new MchSettingForm();
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
