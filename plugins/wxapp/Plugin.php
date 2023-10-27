<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */

namespace app\plugins\wxapp;

use app\forms\admin\CommunicationSettingEditForm;
use app\forms\mall\pay_type_setting\CommonPayType;
use app\helpers\PluginHelper;
use app\models\PayType;
use app\plugins\wxapp\forms\Communication;
use app\plugins\wxapp\forms\Enum;
use app\plugins\wxapp\forms\QrcodeServe;
use app\plugins\wxapp\forms\subscribe\SubscribeForm;
use app\plugins\wxapp\forms\subscribe\SubscribeSend;
use app\plugins\wxapp\forms\ThirdWechat;
use app\plugins\wxapp\forms\WechatOverseasPay;
use app\plugins\wxapp\forms\WechatServicePay;
use app\plugins\wxapp\forms\WechatV3Pay;
use app\plugins\wxapp\models\shop\ShopFactory;
use app\plugins\wxapp\models\WechatSubscribe;
use app\plugins\wxapp\models\WechatTemplate;
use app\plugins\wxapp\models\WxappConfig;
use app\plugins\wxapp\models\WxappSubscribe;
use app\plugins\wxapp\models\WxappWxminiprograms;
use luweiss\Wechat\Wechat;
use luweiss\Wechat\WechatPay;

class Plugin extends \app\plugins\Plugin
{
    private $wechat;
    private $xWechatPay;
    private $wechatTemplate;
    private $subscribe;

    public function getMenus()
    {
        $menus = [
            [
                'name' => \Yii::t('plugins/wxapp', '基础配置'),
                'route' => 'plugin/wxapp/wx-app-config/setting',
                'icon' => 'el-icon-setting',
                'action' => [
                    [
                        'name' => '注册小程序编辑',
                        'route' => 'plugin/wxapp/third-platform/fast-create',
                    ],
                    [
                        'name' => '注册小程序编辑列表',
                        'route' => 'plugin/wxapp/third-platform/fast-create-list',
                    ],
                ],
            ],
//            [
//                'name' => '消息通知',
//                'route' => 'plugin/wxapp/template-msg/setting',
//                'icon' => 'el-icon-setting',
//            ],
//            [
//                'name' => '单商户小程序',
//                'route' => 'plugin/wxapp/app-upload/no-mch',
//                'icon' => 'el-icon-setting',
//            ],
        ];
        // 为了超管可以配置小程序并上传代码
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo, true);
        if(!in_array('wxplatform', $permission)){
            $menus[] = [
                'name' => \Yii::t('plugins/wxapp', '小程序发布'),
                'route' => 'plugin/wxapp/app-upload',
                'icon' => 'el-icon-setting',
            ];
        }
        return $menus;
    }

    public function getIndexRoute()
    {
        return 'plugin/wxapp/wx-app-config/setting';
    }

    /**
     * @param int $type 1:普通商户 2:普通商户/服务商(根据配置获取)
     * @return WechatPay
     * @throws \Exception
     */
    public function getWechatPay($type = Enum::WECHAT_PAY_COMMON)
    {
        if ($this->xWechatPay) {
            return $this->xWechatPay;
        }
        $newPayType = (CommonPayType::get('wxapp'))['wx'];
        if ($newPayType) {
            $payType = PayType::findOne($newPayType);
            $config = [
                'v3key' => $payType->v3key,
                'is_v3' => $payType->is_v3,
            ];
            switch($payType->channel) { // 支付渠道 @czs
                case 5: // 通联支付
                    if ($payType->cert_pem && $payType->key_pem) {
                        $this->generatePem($config, $payType->cert_pem, $payType->key_pem);
                    }
                    $this->xWechatPay = new Communication(array_merge([
                        'appId' => $payType->appid,
                        'mchId' => $payType->mchid,
                        'key' => $payType->key,
                        'tl_merchantId' => $payType->tl_merchantId,
                        'storeConfig' => (new CommunicationSettingEditForm())->getOption(),
                    ], $config));
                    break;
                case 4: // 官方海外微信渠道支付
                    if ($payType->cert_pem && $payType->key_pem) {
                        $this->generatePem($config, $payType->cert_pem, $payType->key_pem);
                    }
                    $this->xWechatPay = new WechatOverseasPay(array_merge([
                        'appId' => $payType->appid,
                        'mchId' => $payType->mchid,
                        'key' => $payType->key,
                        'currency' => $payType->currency,
                    ], $config));
                    break;
                case 1: // 官方渠道支付
                    if ($payType->is_service) {
                        if ($payType->service_cert_pem && $payType->service_key_pem) {
                            $this->generatePem($config, $payType->service_cert_pem, $payType->service_key_pem);
                        }
                        $this->xWechatPay = new WechatServicePay(array_merge([
                            'appId' => $payType->service_appid,
                            'mchId' => $payType->service_mchid,
                            'key' => $payType->service_key,
                            'sub_appid' => $payType->appid,
                            'sub_mch_id' => $payType->mchid,
                        ], $config));
                    } else {
                        if ($payType->cert_pem && $payType->key_pem) {
                            $this->generatePem($config, $payType->cert_pem, $payType->key_pem);
                        }
                        $this->xWechatPay = new WechatV3Pay(array_merge([
                            'appId' => $payType->appid,
                            'mchId' => $payType->mchid,
                            'key' => $payType->key,
                        ], $config));
                    }
                    break;
            }
        } else {
            $third = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
            /**@var WxappConfig $wxappConfig**/
            $wxappConfig = WxappConfig::find()
                ->with(['service'])
                ->where([
                    'mall_id' => \Yii::$app->mall->id,
                ])
                ->one();
            if (!$wxappConfig) {
                throw new \Exception('微信小程序支付尚未配置。');
            }

            $config = [];

            if ($wxappConfig->service && $wxappConfig->service->is_choise == 1 && $type === 2) {
                if ($wxappConfig->service->cert_pem && $wxappConfig->service->key_pem) {
                    $this->generatePem($config, $wxappConfig->service->cert_pem, $wxappConfig->service->key_pem);
                }
                $this->xWechatPay = new WechatServicePay(array_merge([
                    'appId' => $wxappConfig->service->appid,
                    'mchId' => $wxappConfig->service->mchid,
                    'key' => $wxappConfig->service->key,
                    'sub_appid' => $third ? $third->authorizer_appid : $wxappConfig->appid,
                    'sub_mch_id' => $wxappConfig->mchid
                ], $config));
            } else {
                if ($wxappConfig->cert_pem && $wxappConfig->key_pem) {
                    $this->generatePem($config, $wxappConfig->cert_pem, $wxappConfig->key_pem);
                }
                $this->xWechatPay = new WechatV3Pay(array_merge([
                    'appId' => $third ? $third->authorizer_appid : $wxappConfig->appid,
                    'mchId' => $wxappConfig->mchid,
                    'key' => $wxappConfig->key,
                ], $config));
            }
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
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'wxapp';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/wxapp', '微信小程序');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getAccessToken()
    {
        return $this->getWechat()->getAccessToken();
    }

    /**
     * @return array
     * 订阅消息
     */
    public function templateInfoList()
    {
        return [
//            'order_pay_tpl' => [
//                'id' => 'AT0009',
//                'keyword_id_list' => [5, 6, 11, 4],
//                'title' => '订单支付成功通知'
//            ],
            'order_pay_tpl' => [
                'id' => 'AT0229',
                'keyword_id_list' => [2, 9, 1, 34],
                'title' => \Yii::t('plugins/wxapp', '下单成功通知')
            ],
            'order_cancel_tpl' => [
                'id' => 'AT0024',
                'keyword_id_list' => [24, 5, 4, 1],
                'title' => \Yii::t('plugins/wxapp', '订单取消通知')
            ],
            'order_send_tpl' => [
                'id' => 'AT0007',
                'keyword_id_list' => [5, 2, 23, 55],
                'title' => \Yii::t('plugins/wxapp', '订单发货提醒')
            ],
            'order_refund_tpl' => [
                'id' => 'AT0036',
                'keyword_id_list' => [33, 13, 3, 4],
                'title' => \Yii::t('plugins/wxapp', '退款通知')
            ],
//            'enroll_success_tpl' => [
//                'id' => 'AT0027',
//                'keyword_id_list' => [6, 5, 18],
//                'title' => '报名成功通知'
//            ],
            'enroll_success_tpl' => [
                'id' => 'AT0276',
                'keyword_id_list' => [8, 9, 10],
                'title' => \Yii::t('plugins/wxapp', '信息提交成功通知')
            ],
            'enroll_error_tpl' => [
                'id' => 'AT0028',
                'keyword_id_list' => [6, 1, 7],
                'title' => \Yii::t('plugins/wxapp', '报名失败通知')
            ],
            'account_change_tpl' => [
                'id' => 'AT0677',
                'keyword_id_list' => [1, 3],
                'title' => \Yii::t('plugins/wxapp', '账户变动提醒')
            ],
            'audit_result_tpl' => [
                'id' => 'AT0146',
                'keyword_id_list' => [33, 1],
                'title' => \Yii::t('plugins/wxapp', '审核结果通知')
            ],
            'withdraw_success_tpl' => [
                'id' => 'AT0830',
//                'keyword_id_list' => [5, 8, 4],
                'keyword_id_list' => [1, 2, 5, 3, 6],
                'title' => \Yii::t('plugins/wxapp', '提现到账通知')
            ],
            'withdraw_error_tpl' => [
                'id' => 'AT1242',
//                'keyword_id_list' => [3, 5],
                'keyword_id_list' => [5, 11, 3, 6],
                'title' => \Yii::t('plugins/wxapp', '提现失败通知')
            ],
            'share_audit_tpl' => [
                'id' => 'AT0674',
//                'keyword_id_list' => [2, 4],
                'keyword_id_list' => [1, 34, 6, 4],
                'title' => \Yii::t('plugins/wxapp', '审核状态通知')
            ],
        ];
    }

    /**
     * @return WechatTemplate
     * @throws \Exception
     * 微信订阅消息发送
     */
    public function getWechatTemplate()
    {
        $this->wechatTemplate = new WechatTemplate([
            'accessToken' => $this->getAccessToken()
        ]);
        return $this->wechatTemplate;
    }

    //商品详情路径
    public static function getGoodsUrl($item)
    {
        return sprintf("/pages/goods/goods?id=%u", $item['id']);
    }


    /**
     * @return Wechat
     * @throws \luweiss\Wechat\WechatException
     */
    public function getWechat($refresh = false)
    {
        if ($this->wechat && !$refresh) {
            return $this->wechat;
        }
        $third = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        if ($third) {
            $this->wechat = new ThirdWechat([
                'appId' => $third->authorizer_appid,
                'miniprogram' => $third
            ]);
            return $this->wechat;
        }
        $wxappConfig = WxappConfig::findOne(['mall_id' => \Yii::$app->mall->id]);
        if (!$wxappConfig || !$wxappConfig->appid || !$wxappConfig->appsecret) {
            throw new \Exception('小程序信息尚未配置。');
        }
        $this->wechat = new Wechat([
            'appId' => $wxappConfig->appid,
            'appSecret' => $wxappConfig->appsecret,
            'cache' => [
                'target' => Wechat::CACHE_TARGET_FILE,
                'dir' => \Yii::$app->runtimePath . '/wechat-cache',
            ],
        ]);
        return $this->wechat;
    }

    public function getIsPlatformPlugin()
    {
        return true;
    }

    /**
     * @param string|array $param
     * @return array|\yii\db\ActiveRecord[]|WxappSubscribe[]
     * 获取所有订阅消息
     */
    public function getTemplateList($param = '*')
    {
        $model = new SubscribeForm();

        return $model->getTemplateList($param);
    }

    /**
     * @param array $attributes
     * @return bool
     * @throws \Exception
     * 后台保存订阅消息
     */
    public function addTemplateList($attributes)
    {
        $model = new SubscribeForm();
        return $model->addTemplateList($attributes);
    }

    /**
     * @param $templateList
     * @return array
     * @throws \Exception
     * 微信小程序后台添加订阅消息
     */
    public function addTemplate($templateList)
    {
        $model = new SubscribeForm();
        return $model->addTemplate($templateList);
    }

    /**
     * @return SubscribeSend|null
     * 消息发送接口
     */
    public function templateSender()
    {
        return new SubscribeSend();
    }

    /**
     * @return WechatSubscribe
     * @throws \Exception
     * 微信订阅消息接口
     */
    public function getSubscribe()
    {
        if(!$this->subscribe) {
            $this->subscribe = new WechatSubscribe([
                'accessToken' => $this->getAccessToken()
            ]);
        }
        return $this->subscribe;
    }

    // 获取平台图标
    public function getPlatformIconUrl()
    {
        return [
            [
                'key' => $this->getName(),
                'name' => $this->getDisplayName(),
                'icon' => PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img/wxapp.png'
            ]
        ];
    }

    public function getQrcodeServe($config = [])
    {
        return new QrcodeServe($config);
    }

    /**
     * @return ShopFactory
     * @throws \Exception
     * 自定义交易组件
     */
    public function getShopService()
    {
        return ShopFactory::create([
            'access_token' => $this->getAccessToken()
        ]);
    }
}
