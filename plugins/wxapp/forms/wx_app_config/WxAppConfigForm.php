<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\wxapp\forms\wx_app_config;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\forms\open3rd\ExtAppForm;
use app\models\Model;
use app\models\Option;
use app\plugins\wxapp\models\WxappWxminiprograms;
use app\plugins\wxapp\models\WxappConfig;
use yii\helpers\ArrayHelper;

class WxAppConfigForm extends Model
{
    public $id;
    public $page;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '微信配置ID',
        ];
    }

    public function getDetail()
    {
        $third = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        if ($third) {
            $third = ArrayHelper::toArray($third);
            if (!empty($third['domain'])) {
                $third['domain'] = explode(",", trim($third['domain'], ','));
            } else {
                $third['domain'] = [];
            }
            $third['user_privacy'] = 0;
            if(CommonOption::get(Option::NAME_WX_MINI_PRIVACY, \Yii::$app->mall->id, Option::GROUP_APP)){
                $third['user_privacy'] = 1;
            }
        }
        /**@var WxappConfig $detail**/
        $detail = WxappConfig::find()
            ->where(['mall_id' => \Yii::$app->mall->id])
            ->one();

        $newDetail = [];
        if ($detail) {
            $newDetail = ArrayHelper::toArray($detail);
        }
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo, true);

        $data = [
            'has_third_permission' => in_array('wxplatform', $permission),
            'has_fast_create_wxapp_permission' => in_array('fast-create-wxapp', $permission),
        ];
        if ($third) {
            $data['third'] = $third;
        }
        if ($detail) {
            $data['detail'] = $newDetail;
        }
        if (!$third && !$detail) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '信息未配置',
                'data' => $data
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => $data
        ];
    }

    public function getOption(){
        return CommonOption::get(Option::NAME_WX_MINI_PRIVACY, \Yii::$app->mall->id, Option::GROUP_APP);
    }

    /**
     * 获取微信第三方隐私协议
     * @return array
     * @czs
     */
    public function getPrivacyInfo(){
        $option = $this->getOption();
        $settingList = ArrayHelper::toArray(ExtAppForm::instance()->getPrivacySetting(2));
        if($settingList['errcode'] !== 0){
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $settingList['errmsg'],];
        }
        $content = [
            'owner_setting' => $option['owner_setting'] ?? [
                    'contact_email' => '',
                    'contact_phone' => '',
                    'contact_qq' => '',
                    'contact_weixin' => '',
                    'notice_method' => '',
                ],
            'setting_list' => $settingList['setting_list'],
        ]; // 配置内容
        $labelValue = [
            'UserInfo' => '帮助用户完善并展示个人信息',
            'Location' => '帮助用户完善收货地址信息及显示距离',
            'Address' => '帮助用户完善收货地址信息',
            'Invoice' => '维护消费功能并获取发票信息',
            'RunData' => '根据步数兑换奖励',
            'Record' => '录制音频进行即时通讯',
            'Album' => '帮助用户进行售后及内容分享',
            'Camera' => '提供扫码、售后服务等功能',
            'PhoneNumber' => '帮助用户登录注册并完善收货信息',
            'EXOrderInfo' => '提供收发货及售后维权等服务',
            'EXUserPublishContent' => '提供商品评价等服务',
            'AlbumWriteOnly' => '提供扫码、图片存储等功能',
            'MessageFile' => '提供图片及文件上传功能',
            'Email' => '绑定用户或发送消息',
        ]; // 默认值
        $nameList = []; // 所有配置项名称
        $allSettingList = []; // 所有配置项
        foreach ($settingList['privacy_desc']['privacy_desc_list'] as $item){
            $nameList[$item['privacy_key']] = $item['privacy_desc'];
            unset($item['privacy_desc']);
            $item['privacy_text'] = '';
            $allSettingList[] = $item;
        }
        if(empty($option['setting_list'])){
            $option['setting_list'] = [];
        }else{
            $option['setting_list'] = array_column($option['setting_list'], "privacy_text", 'privacy_key');
        }
        foreach ($content['setting_list'] as $k => &$item){
            if (!in_array($item['privacy_key'], $settingList['privacy_list'])) {
                unset($content['setting_list'][$k]);
                continue;
            }
            $item['privacy_text'] = $labelValue[$item['privacy_key']] ?? $item['privacy_text'];
            unset($item['privacy_label']);
            if(!empty($option['setting_list'][$item['privacy_key']])){
                $item['privacy_text'] = $option['setting_list'][$item['privacy_key']];
            }
        }
        unset($item);
        $content['setting_list'] = array_values($content['setting_list']);
        foreach ($allSettingList as $k => &$item){
            if (in_array($item['privacy_key'], $settingList['privacy_list'])) {
                unset($allSettingList[$k]);
                continue;
            }
            $item['default_text'] = $labelValue[$item['privacy_key']] ?? $item['privacy_text'];
            if(!empty($option['setting_list'][$item['privacy_key']])){
                $item['privacy_text'] = $option['setting_list'][$item['privacy_key']];
            }
        }
        unset($item);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'name' => $nameList,
                'list' => $content,
                'other' => array_values($allSettingList),
            ]
        ];
    }
}
