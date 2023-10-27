<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c)
 * author: opmall
 */
namespace app\forms\pc;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\helpers\ArrayHelper;

class SettingForm
{
    public $params;

    // 获取广告图
    public function getAdListInfo(){
        $setting = $this->getData(SettingConf::$homeAd);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $setting
        ];
    }

    // 保存广告图
    public function saveAdListInfo(){
        $this->saveData(SettingConf::$homeAd);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    // 获取设置
    public function getBasicSettingInfo(){
        $setting = $this->getData(SettingConf::$basicSetting);
        $setting["pcUrl"] = \Yii::$app->request->hostInfo . "/index.html#/homepage/index?mall_id=" . urlencode(base64_encode(\Yii::$app->mall->id));
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $setting
        ];
    }

    // 保存设置
    public function saveBasicSettingInfo(){
        $this->saveData(SettingConf::$basicSetting);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function getData(array $item, $isDefault = true){
        $data = [];
        foreach ($item as $name => $value){
            $res = CommonOption::get($name, \Yii::$app->mall->id, SettingConf::GROUP_PC, $isDefault ? $value : "");
            if(is_object($res)){
                $res = ArrayHelper::toArray($res);
            }
            $data[$name] = $res;
        }
        return $data;
    }

    public function saveData(array $keyList){
        foreach ($keyList as $name => $value){
            $value = isset($this->params[$name]) ? $this->params[$name] : $value;
            CommonOption::set($name, $value, \Yii::$app->mall->id, SettingConf::GROUP_PC);
        }
        return true;
    }
}
