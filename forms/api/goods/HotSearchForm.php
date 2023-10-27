<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\forms\api\goods;

use app\bootstrap\response\ApiCode;
use app\forms\common\goods\CommonHotSearch;
use app\models\Model;

class HotSearchForm extends Model
{

    public function getList()
    {
        $common = new CommonHotSearch();
        $data = $common->getAll();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '获取成功',
            'data' => [
                'list' => $data
            ],
        ];
    }
}
