<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */

namespace app\plugins\minishop\forms;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\forms\common\CommonUser;
use app\forms\common\platform\PlatformConfig;
use app\models\Model;
use app\models\Option;
use app\models\User;

class SettingForm extends Model
{
    public $keyword;
    public $status;
    public $user_id;

    public function rules()
    {
        return [
            [['status'], 'integer'],
            [["keyword"], 'string'],
            [["user_id"], 'safe'],
        ];
    }

    public function searchUser(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => CommonUser::searchUser($this->keyword)
        ];
    }

    public function getSetting(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $setting = CommonOption::get('minishop_setting', \Yii::$app->mall->id, Option::GROUP_APP);
        $setting = CommonAppConfig::check($setting, $this->getDefault());
        $user_list = [];
        if($setting['user_id']){
            $userList = User::find()->where(['id' => $setting['user_id']])->with('userInfo', 'userPlatform')->all();
            $platformConfig = new PlatformConfig();
            /** @var User[] $userList */
            foreach ($userList as $k => $v) {
                $user_list[] = [
                    'id' => $v->id,
                    'nickname' => $v->nickname,
                    'avatar' => $v->userInfo->avatar,
                    'platform_icon' => $platformConfig->getPlatformIcon($v)
                ];
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting,
                "user_list" => $user_list
            ]
        ];
    }

    private function getDefault()
    {
        return [
            'user_id' => [],
            'status' => 0,
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {

            CommonOption::set('minishop_setting', [
                'status' => $this->status,
                'user_id' => $this->user_id,
            ], \Yii::$app->mall->id, Option::GROUP_APP);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}