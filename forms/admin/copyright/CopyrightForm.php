<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 */

namespace app\forms\admin\copyright;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\models\Model;

class CopyrightForm extends Model
{
    public function getDetail()
    {
        $option = CommonAppConfig::getCoryRight(true);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $option,
            ]
        ];
    }
}
