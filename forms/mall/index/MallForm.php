<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\index;


use app\bootstrap\response\ApiCode;
use app\forms\mall\recharge\RechargeSettingForm;
use app\models\Mall;
use app\models\Model;

class MallForm extends Model
{
    public function getDetail()
    {
        $mall = new Mall();
        $mall = $mall->getMallSetting();
        $rechargeForm = new RechargeSettingForm();
        $setting = $rechargeForm->setting();
        $mall['recharge'] = $setting;
        $mall['permission']['mpvideo'] = false;
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        if (in_array('mpvideo', $permission)) {
            $mall['permission']['mpvideo'] = true;
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $mall
            ],
        ];
    }
}
