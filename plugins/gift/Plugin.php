<?php
/**
 * @copyright ©2021 hook007
 * @author jack_guo
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019年10月11日 14:15:22
 */

namespace app\plugins\gift;

use app\forms\OrderConfig;
use app\handlers\HandlerBase;
use app\helpers\PluginHelper;
use app\plugins\gift\forms\common\CommonGift;
use app\plugins\gift\forms\common\CommonGoods;
use app\plugins\gift\forms\common\GiftConvertTemplate;
use app\plugins\gift\forms\common\GiftConVeryInfo;
use app\plugins\gift\forms\common\GiftFormUserInfo;
use app\plugins\gift\forms\common\GiftFromUserTemplate;
use app\plugins\gift\forms\common\GiftToUserInfo;
use app\plugins\gift\forms\common\GiftToUserTemplate;
use app\plugins\gift\forms\common\LimitBuy;
use app\plugins\gift\forms\mall\StatisticsForm;
use app\plugins\gift\handlers\HandlerRegister;
use app\plugins\gift\handlers\OrderCreatedHandler;
use app\plugins\gift\handlers\OrderPayedHandler;
use app\plugins\gift\handlers\OrderSalesHandler;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/gift', '社交送礼设置'),
                'route' => 'plugin/gift/mall/setting/index',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/gift', '商品管理'),
                'route' => 'plugin/gift/mall/goods/index',
                'icon' => 'el-icon-star-on',
                'action' => [
                    [
                        'name' => '商品管理',
                        'route' => 'plugin/gift/mall/goods/edit',
                    ],
                ]
            ],
//            [
//                'name' => '商品分类',
//                'route' => 'plugin/gift/mall/goods/categories',
//                'icon' => 'el-icon-star-on'
//            ],
            [
                'name' => \Yii::t('plugins/gift', '礼物记录'),
                'route' => 'plugin/gift/mall/record/tribute',
                'icon' => 'el-icon-star-on',
                'action' => [
                    [
                        'name' => '记录详情',
                        'route' => 'plugin/gift/mall/record/tribute-detail',
                    ],
                ]
            ],
            [
                'name' => \Yii::t('plugins/gift', '领取记录'),
                'route' => 'plugin/gift/mall/record/receive',
                'icon' => 'el-icon-star-on',
            ],
//            [
//                'name' => '消息通知',
//                'route' => 'plugin/gift/mall/setting/template',
//                'icon' => 'el-icon-star-on',
//            ],
            $this->getStatisticsMenus(false)
        ];
    }

    public function handler()
    {
        $register = new HandlerRegister();
        $HandlerClasses = $register->getHandlers();
        foreach ($HandlerClasses as $HandlerClass) {
            $handler = new $HandlerClass();
            if ($handler instanceof HandlerBase) {
                /** @var HandlerBase $handler */
                $handler->register();
            }
        }
        return $this;
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'gift';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/gift', '社交送礼');
    }

    public function getAppConfig()
    {
        return [];
    }


    public function getIndexRoute()
    {
        return 'plugin/gift/mall/setting/index';
    }

    public function getPickLink()
    {
        $iconBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img/pick-link';
        return [
            [
                'key' => 'gift',
                'name' => \Yii::t('plugins/gift', '礼物'),
                'open_type' => '',
                'icon' => $iconBaseUrl . '/icon-gift.png',
                'value' => '/plugins/gift/index/index',
            ],
        ];
    }

    public function getOrderConfig()
    {
        $setting = CommonGift::getSetting();
        $config = new OrderConfig([
            'is_sms' => 1,
            'is_print' => 1,
            'is_mail' => 1,
            'is_share' => $setting['is_share'],
            'is_member_price' => $setting['is_member_price']
        ]);
        return $config;
    }

    /**
     * 返回实例化后台统计数据接口
     * @return object
     */
    public function getApi()
    {
        return new StatisticsForm();
    }

    public function getStatisticsMenus($bool = true)
    {
        return [
            'is_statistics_show' => $bool,
            'name' => $bool ? $this->getDisplayName() : \Yii::t('plugins/gift', '插件统计'),
            'key' => $this->getName(),
            'pic_url' => $this->getStatisticIconUrl(),
            'route' => 'mall/gift-statistics/index',
        ];
    }

    public function getSmsSetting()
    {
        return [
            'gift_lottery' => [
                'title' => \Yii::t('plugins/gift', '抽奖结果通知'),
                'content' => \Yii::t('plugins/gift', '您参与的送礼物活动结果为').'${code}'.\Yii::t('plugins/gift', '请登录商城查看'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [
                    [
                        'key' => 'code',
                        'value' => \Yii::t('plugins/gift', '模板变量'),
                        'desc' => \Yii::t('plugins/gift', '您参与的送礼物活动结果为').'${code}'.\Yii::t('plugins/gift', '则只需填写code')
                    ]
                ],
                'key' => 'user'
            ],
            'gift' => [
                'title' => \Yii::t('plugins/gift', '礼物到期提醒'),
                'content' => \Yii::t('plugins/gift', '您收到的礼物即将到期'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [],
                'key' => 'user'
            ],
            'gift_refund' => [
                'title' => \Yii::t('plugins/gift', '送礼失败退款提醒'),
                'content' => \Yii::t('plugins/gift', '您送的礼物因领取超时已自动退款'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [],
                'key' => 'user'
            ],
        ];
    }

    //商品详情路径
    public function getGoodsUrl($item)
    {
        return sprintf("/plugins/gift/goods/goods?id=%u", $item['id']);
    }

    public function getOrderPayedHandleClass()
    {
        return new OrderPayedHandler(); // TODO: Change the autogenerated stub
    }

    public function getOrderSalesHandleClass()
    {
        return new OrderSalesHandler(); // TODO: Change the autogenerated stub
    }

    public function getOrderCreatedHandleClass()
    {
        return new OrderCreatedHandler(); // TODO: Change the autogenerated stub
    }

    public function templateList()
    {
        return [
            'gift_to_user' => GiftToUserTemplate::class,
            'gift_convert' => GiftConvertTemplate::class,
            'gift_form_user' => GiftFromUserTemplate::class,
        ];
    }

    public function getEnableVipDiscount()
    {
        $setting = CommonGift::getSetting();
        return $setting['svip_status'] == 0 ? false : true;
    }

    public function getBlackList()
    {
        return [
            'plugin/gift/api/gift-order/order-submit',
        ];
    }


    public function getGoodsData($array)
    {
        return CommonGoods::getCommon()->getDiyGoods($array);
    }

    public function getOrderAction($actionList, $order)
    {
        if ($order->status = 0) {
            $actionList['is_show_comment'] = 0;
        }

        return $actionList;
    }

    public function getEnableFullReduce()
    {
        $setting = CommonGift::getSetting();
        return $setting['is_full_reduce'] == 0 ? false : true;
    }

    public function templateRegister()
    {
        return [
            GiftConVeryInfo::TPL_NAME => GiftConVeryInfo::class,
            GiftFormUserInfo::TPL_NAME => GiftFormUserInfo::class,
            GiftToUserInfo::TPL_NAME => GiftToUserInfo::class,
        ];
    }

    public function goodsAuth()
    {
        $config = parent::goodsAuth();
        $config['is_show_and_buy_auth'] = false;
        $config['is_time'] = false;
        return $config;
    }

    public function limitBuy($config = [])
    {
        return new LimitBuy($config);
    }
}
