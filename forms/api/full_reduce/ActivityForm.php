<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/7/16
 * Time: 11:54
 */

namespace app\forms\api\full_reduce;

use app\bootstrap\response\ApiCode;
use app\forms\common\full_reduce\CommonActivity;
use app\models\Model;

class ActivityForm extends Model
{
    public function getActivity()
    {
        $info = CommonActivity::getActivityMarket();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => (object)$info,
        ];
    }
}
