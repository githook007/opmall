<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class CommunicationSettingEditForm extends Model
{

    public $orgid;
    public $tl_appid;
    public $tl_rsaPrivateKey;

    public function rules()
    {
        return [
            [['orgid', 'tl_appid', 'tl_rsaPrivateKey'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $data = $this->attributes;
            CommonOption::set('communication_setting', $data, 0, Option::GROUP_ADMIN);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }

    public function getSetting()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $setting = $this->getOption();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'setting' => $setting
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }

    public function getOption()
    {
        $setting = CommonOption::get('communication_setting', 0, Option::GROUP_ADMIN, $this->getDefault());
        return $setting;
    }

    public function getDefault()
    {
        return [
            'orgid' =>  '',
            'tl_appid' =>  '',
            'tl_rsaPrivateKey' =>  '',
        ];
    }
}
