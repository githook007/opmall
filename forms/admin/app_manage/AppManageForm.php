<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\app_manage;

use app\bootstrap\response\ApiCode;
use app\models\AppManage;
use app\models\Model;
use app\plugins\Plugin;

class AppManageForm extends Model
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

        try {
            /** @var AppManage $appManage */
            $appManage = AppManage::find()->andWhere(['name' => $this->name, 'is_delete' => 0])->one();
            if ($appManage) {
                $data = [
                    'name' => $appManage->name,
                    'display_name' => $appManage->display_name,
                    'pic_url_type' => $appManage->pic_url_type,
                    'pic_url' => $appManage->pic_url,
                    'content' => $appManage->content,
                    'is_show' => $appManage->is_show,
                    'pay_type' => $appManage->pay_type,
                    'price' => $appManage->price,
                    'detail' => $appManage->detail,
                    'external_link' => $appManage->external_link,
                ];
            } else {
                $Class = '\\app\\plugins\\' . $this->name . '\\Plugin';
                if (!class_exists($Class)) {
                    throw new \Exception("插件不存在。");
                }
                /** @var Plugin $plugin */
                $plugin = new $Class();
                $data = [
                    'name' => $plugin->getName(),
                    'display_name' => $plugin->getDisplayName(),
                    'pic_url_type' => 1,
                    'pic_url' => $plugin->getIconUrl(),
                    'content' => $plugin->getContent(),
                    'is_show' => 1,
                    'pay_type' => 'service',
                    'price' => '',
                    'detail' => '',
                    'external_link' => ''
                ];
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $data
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }
}
