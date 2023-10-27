<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/2
 * Time: 13:39
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\components;


use app\bootstrap\response\ApiCode;
use app\plugins\community\forms\common\CommonSetting;
use yii\base\Action;

class SettingDataAction extends Action
{
    public function run()
    {
        $setting = CommonSetting::getCommon()->getSetting();
        \Yii::$app->response->data = [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => $setting
        ];
    }
}
