<?php
/**
 * @copyright ©2018 hook007
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */


namespace app\plugins\app;

use app\forms\mall\pay_type_setting\CommonPayType;
use app\helpers\PluginHelper;
use app\models\PayType;
use app\plugins\app\forms\common\CommonSetting;
use app\plugins\app\forms\common\QrcodeServe;
use app\plugins\app\forms\common\WechatPay;

class Plugin extends \app\plugins\Plugin
{
    private $xWechatPay;

    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/app', '基础配置'),
                'route' => 'plugin/app/mall/config/index',
                'icon' => 'el-icon-setting',
            ]
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/app/mall/config/index';
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'app';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/app', 'APP商城');
    }

    /**
     * @param array $config
     * @return QrcodeServe
     */
    public function getQrcodeServe($config = [])
    {
        return new QrcodeServe($config);
    }

    /**
     * 插件的配置
     * @return array
     */
    public function getAppConfig()
    {
        if(\Yii::$app->appPlatform == APP_PLATFORM_APP) {
            return CommonSetting::getCommon()->getSetting('', false);
        }else{
            return [];
        }
    }

    public function getIsPlatformPlugin()
    {
        return true;
    }

    /**
     * @param int $type 1:普通商户 2:普通商户/服务商(根据配置获取)
     */
    public function getWechatPay($type = 2)
    {
        if ($this->xWechatPay) {
            return $this->xWechatPay;
        }
        $newPayType = (CommonPayType::get('app'))['wx'];
        if ($newPayType) {
            $payType = PayType::findOne($newPayType);
            $config = [];
            switch($payType->channel) { // 支付渠道 @czs
                case 1: // 官方渠道支付
                    if ($payType->is_service) {
                        if ($payType->service_cert_pem && $payType->service_key_pem) {
                            $this->generatePem($config, $payType->service_cert_pem, $payType->service_key_pem);
                        }
                        $this->xWechatPay = new WechatPay(array_merge([
                            'appId' => $payType->service_appid,
                            'mchId' => $payType->service_mchid,
                            'key' => $payType->service_key,
                            'sub_appid' => $payType->appid,
                            'sub_mch_id' => $payType->mchid,
                            'v3key' => $payType->v3key,
                            'is_v3' => $payType->is_v3,
                        ], $config));
                    } else {
                        if ($payType->cert_pem && $payType->key_pem) {
                            $this->generatePem($config, $payType->cert_pem, $payType->key_pem);
                        }
                        $this->xWechatPay = new WechatPay(array_merge([
                            'appId' => $payType->appid,
                            'mchId' => $payType->mchid,
                            'key' => $payType->key,
                            'v3key' => $payType->v3key,
                            'is_v3' => $payType->is_v3,
                        ], $config));
                    }
                    break;
            }
            if(!$this->xWechatPay){
                throw new \Exception('微信支付必须是官方');
            }
        }else{
            throw new \Exception('支付信息设置有误。请联系管理员');
        }
        return $this->xWechatPay;
    }

    /**
     * @param $config
     * @param $cert_pem
     * @param $key_pem
     */
    private function generatePem(&$config, $cert_pem, $key_pem)
    {
        $pemDir = \Yii::$app->runtimePath . '/pem';
        make_dir($pemDir);
        $certPemFile = $pemDir . '/' . md5($cert_pem);
        if (!file_exists($certPemFile)) {
            file_put_contents($certPemFile, $cert_pem);
        }
        $keyPemFile = $pemDir . '/' . md5($key_pem);
        if (!file_exists($keyPemFile)) {
            file_put_contents($keyPemFile, $key_pem);
        }
        $config['certPemFile'] = $certPemFile;
        $config['keyPemFile'] = $keyPemFile;
        return $config;
    }

    // 获取平台图标
    public function getPlatformIconUrl()
    {
        return [
            [
                'key' => $this->getName(),
                'name' => $this->getDisplayName(),
                'icon' => PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img/app.png'
            ]
        ];
    }

    public function updateConfig($appData){
        $agreement = isset($appData['agreement']) ? @gzuncompress(@base64_decode($appData['agreement'])) : '';
        $agreement = (array)@json_decode($agreement, true);
        if($agreement) {
            $setting = CommonSetting::getCommon();
            $data = [
                'agreement_type' => empty(($agreement['isUrl'] ?? '')) ? 1 : 2,
                "agreement_content" => $agreement['value'] ?? '',
                "agreement_link" => $agreement['url'] ?? ''
            ];
            $setting->setSetting($data, CommonSetting::APP_AGREEMENT_SETTING);
        }
    }

//    public function templateSender()
//    {
//        return new TemplateSend();
//    }

//    public function getNotSupport()
//    {
//        return [
//            'navbar' => [
//                '/plugins/step/index/index',
//                '/pages/live/index',
//                'plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin',
//                '/pages/binding/binding',
//            ],
//            'home_nav' => [
//                '/plugins/step/index/index',
//                '/pages/live/index',
//                'plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin',
//                '/pages/binding/binding',
//            ],
//            'user_center' => [
//                '/plugins/step/index/index',
//                '/pages/live/index',
//                'plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin',
//                '/pages/binding/binding',
//            ],
//        ];
//    }

//    public function getSmsSetting()
//    {
//        return [];
//    }

//    public function templateList()
//    {
//        return [];
//    }
}
