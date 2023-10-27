<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\role_setting;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class RoleSettingEditForm extends Model
{
    public $data;

    public function save()
    {
        try {
            $res = CommonOption::set(Option::NAME_ROLE_SETTING, $this->data, \Yii::$app->mall->id, Option::GROUP_ADMIN);

            if (!$res) {
                throw new \Exception('保存失败');
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
