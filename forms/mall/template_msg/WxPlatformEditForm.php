<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\template_msg;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class WxPlatformEditForm extends Model
{
    public $app_id;
    public $app_secret;
    public $admin_open_list;
    public $template_list;
    public $show_user;
    public $user_template;

    public function rules()
    {
        return [
            [['app_id', 'app_secret'], 'string'],
            [['admin_open_list'], 'safe'],
            [['template_list', 'user_template'], 'safe'],
            ['show_user', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => '公众号AppId',
            'app_secret' => '公众号AppSecret',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $option = CommonOption::set(
                Option::NAME_WX_PLATFORM,
                $this->attributes,
                \Yii::$app->mall->id,
                Option::GROUP_APP
            );

            if (!$option) {
                throw new \Exception('保存失败');
            }
            try {
                $plugin = \Yii::$app->plugin->getPlugin('wechat');
                if ($plugin) {
                    if (method_exists($plugin, 'addTemplateList')) {
                        $res = $plugin->addTemplateList($this->user_template);
                    }
                }
            } catch (\Exception $exception) {
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
