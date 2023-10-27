<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/18
 * Time: 16:03
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\forms\api;


use app\bootstrap\response\ApiCode;
use app\plugins\bargain\forms\common\CommonSetting;

class IndexForm extends ApiModel
{
    public function search()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => CommonSetting::getCommon()->getList()
        ];
    }
}
