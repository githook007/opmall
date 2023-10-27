<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/9/29
 * Time: 5:00 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\common;

use app\forms\common\CommonOption;
use app\helpers\ArrayHelper;
use app\models\Model;

class CommonSetting extends Model
{
    /* @var CommonSetting $instance */
    public static $instance;
    public $mall;
//    public const REGISTER_SETTING = 'app_register_setting';
//    public const APP_CONTACT = 'app_contact';
    public const APP_BAIDU_MAP = 'app_baidu_map';
    public const APP_WX_CONFIG = 'app_wx_config';
    public const APP_AGREEMENT_SETTING = 'app_agreement_setting'; // 协议配置

    /**
     * @param null $mall
     * @return CommonSetting
     */
    public static function getCommon($mall = null)
    {
        if (!$mall) {
            $mall = \Yii::$app->mall;
        }
        if (self::$instance && self::$instance->mall->id == $mall->id) {
            return self::$instance;
        }
        self::$instance = new self();
        self::$instance->mall = $mall;
        return self::$instance;
    }

    public function getConfig($index = '')
    {
        $config = [
            self::APP_WX_CONFIG => [
                [
                    'key' => 'app_id',
                    'name' => '移动应用AppID',
                    'default' => '',
                ],
                [
                    'key' => 'app_secret',
                    'name' => '移动应用AppSecret',
                    'default' => '',
                ]
            ],
            self::APP_BAIDU_MAP => [
                [
                    'key' => 'baidu_android_ak',
                    'name' => 'android百度AK',
                    'default' => '',
                ],
                [
                    'key' => 'baidu_ios_ak',
                    'name' => 'ios百度AK',
                    'default' => '',
                ],
            ],
            self::APP_AGREEMENT_SETTING => [
                [
                    'key' => 'agreement_type',
                    'name' => '协议类型',
                    'default' => '1',
                ],
                [
                    'key' => 'agreement_content',
                    'name' => '协议内容',
                    'default' => '',
                ],
                [
                    'key' => 'agreement_link',
                    'name' => '协议链接',
                    'default' => '',
                ],
            ]
        ];
        return isset($config[$index]) ? [$index => $config[$index]] : $config;
    }

    public function getName($index = ''){
        $config = $this->getConfig($index);
        $data = [];
        foreach ($config as $name => $item){
            $data = array_merge($data, array_column($item, 'name', 'key'));
        }
        return $data;
    }

    public function getSetting($index = '', $isBack = true)
    {
        $config = $this->getConfig($index);
        $data = [];
        foreach ($config as $name => $default){
            $setting = CommonOption::get($name, $this->mall->id, 'plugin');
            $data = ArrayHelper::merge($data, $this->checkDefault($setting, array_column($default, 'default', 'key')));
        }
        foreach (['agreement_type'] as $key){
            if(isset($data[$key])){
                $data[$key] = intval($data[$key]);
            }
        }
        if(!$isBack){
            if(isset($data['app_secret'])){
                unset($data['app_secret']);
            }
            if(isset($data['app_id'])){
                $data['domain_url'] = \Yii::$app->request->hostInfo.'/';
            }
        }
        return $data;
    }

    public function setSetting($data, $index = '')
    {
        if(!$data){
            return false;
        }
        $config = $this->getConfig($index);
        foreach ($config as $name => $default){
            $set = [];
            foreach ($default as $item){
                $set[$item['key']] = $data[$item['key']] ?? $item['default'];
            }
            CommonOption::set($name, $set, $this->mall->id, 'plugin');
        }
        return true;
    }

    /**
     * @param array $data
     * @param array $default
     * @return array
     * 处理新增的默认数据
     */
    public function checkDefault($data, $default)
    {
        foreach ($default as $key => $item) {
            if (!isset($data[$key])) {
                $data[$key] = $item;
                continue;
            }
            if (is_array($item)) {
                $data[$key] = $this->checkDefault($data[$key], $item);
            }
        }
        return $data;
    }
}
