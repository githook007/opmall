<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/18
 * Time: 17:56
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\diy\forms\mall;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\diy\models\CoreTemplateType;
use Yii;

class SyncTemplateTypeForm extends Model
{
    public function sync()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => 'sync ok.',
        ];
    }
}
