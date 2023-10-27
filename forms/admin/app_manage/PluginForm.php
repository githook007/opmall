<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\app_manage;

use app\plugins\Plugin;
use app\bootstrap\response\ApiCode;
use app\forms\admin\PaySettingForm;
use app\models\AppManage;
use app\models\AppOrder;
use app\models\Model;

class PluginForm extends Model
{
    public $name;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '应用标识'
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $name = $this->name;
        $Class = '\\app\\plugins\\' . $name . '\\Plugin';
        if (!class_exists($Class)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '插件不存在。',
            ];
        }
        /** @var Plugin $plugin */
        $plugin = new $Class();
        $data = [
            'id' => null,
            'name' => $plugin->getName(),
            'display_name' => $plugin->getDisplayName(),
            'pic_url' => $plugin->getIconUrl(),
            'content' => $plugin->getContent(),
            'type' => 'local',
            'version' => $plugin->getVersionFileContent(),
            'new_version' => false,
            'desc' => '',
        ];
        $data['installed_plugin'] = \Yii::$app->plugin->getInstalledPlugin($data['name']);

        $appManage = AppManage::findOne(['name' => $plugin->getName(), 'is_delete' => 0]);
        if ($appManage) {
            $data['display_name'] = $appManage->display_name;
            $data['desc'] = $appManage->content;
            $data['content'] = $appManage->detail;
            $data['pic_url'] = $appManage->pic_url_type == 1 ? $data['pic_url'] : $appManage->pic_url;
        }

        $data['is_buy'] = true;
        if (!\Yii::$app->role->checkPlugin($plugin)) {
            $data['is_buy'] = false;
        }

        $appOrder = AppOrder::find()->andWhere([
            'name' => $plugin->getName(),
            'user_id' => \Yii::$app->user->id,
            'is_delete' => 0,
            'is_pay' => 1
        ])->one();
        $data['app_order'] = $appOrder;

        $data['is_super_admin'] = \Yii::$app->role->isSuperAdmin;
        $data['app_manage'] = $appManage;

        $setting = (new PaySettingForm())->getOption();
        $data['setting'] = $setting;
        $data['random_number'] = rand(0, count($setting['customer_service_list']) - 1);
        
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $data,
        ];
    }
}
