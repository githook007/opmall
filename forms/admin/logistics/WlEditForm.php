<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\logistics;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class WlEditForm extends Model
{
    public $appId;
    public $secret;
    public $status;
    public $storeId;
    public $is_prod;

    public function rules()
    {
        return [
            [['status', 'is_prod'], 'integer'],
            [['appId', 'secret', 'storeId'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $data = $this->attributes;
            CommonOption::set('open_wlhulian_setting', $data, 0, Option::GROUP_ADMIN);
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
}
