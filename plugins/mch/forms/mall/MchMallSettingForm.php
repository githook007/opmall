<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\mch\forms\common\MallSettingForm;

class MchMallSettingForm extends Model
{
    public $mch_id;

    public function rules()
    {
        return [
            [['mch_id'], 'integer']
        ];
    }

    public function getSetting()
    {
        $setting = (new MallSettingForm())->search($this->mch_id);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting,
            ]
        ];
    }
}
