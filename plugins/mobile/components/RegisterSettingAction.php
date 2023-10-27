<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/10/14
 * Time: 4:18 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\mobile\components;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\plugins\mobile\forms\common\CommonSetting;
use yii\base\Action;

class RegisterSettingAction extends Action
{
    public function run()
    {
        $setting = CommonSetting::getCommon()->getRegisterSetting();
        $list = CommonOption::get(CommonSetting::H5_CONTACT, \Yii::$app->mall->id, 'plugin', []);
        $setting['list'] = $list;
        \Yii::$app->response->data = [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => $setting
        ];
    }
}
