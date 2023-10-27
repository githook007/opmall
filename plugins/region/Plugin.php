<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * User: jack_guo
 * Date: 2019/7/3
 * Time: 14:12
 */

namespace app\plugins\region;

use app\handlers\HandlerBase;
use app\plugins\region\forms\common\CommonRegion;
use app\plugins\region\forms\common\RegionReview;
use app\plugins\region\forms\mall\RegionEditForm;
use app\plugins\region\forms\mall\RegionForm;
use app\plugins\region\forms\mall\SettingForm;
use app\plugins\region\forms\mall\StatisticsForm;
use app\plugins\region\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/region', '区域代理设置'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/region/mall/setting/index',
            ],
            [
                'name' => \Yii::t('plugins/region', '代理管理'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/region/mall/region/index'
            ],
            [
                'name' => \Yii::t('plugins/region', '代理级别'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/region/mall/level/index',
            ],
            [
                'name' => \Yii::t('plugins/region', '分红结算'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/region/mall/balance/index',
                'action' => [
                    [
                        'name' => '分红结算',
                        'route' => 'plugin/region/mall/balance/add',
                    ],
                    [
                        'name' => '结算详情',
                        'route' => 'plugin/region/mall/balance/detail'
                    ]
                ]
            ],
            [
                'name' => \Yii::t('plugins/region', '分红订单'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/region/mall/order/index',
                'action' => [
                    [
                        'name' => '订单详情',
                        'route' => 'plugin/region/mall/order/detail',
                    ]
                ]
            ],
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
        return 'region';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/region', '区域代理');
    }

    public function getAppConfig()
    {
        return [];
    }

    /**
     * 返回实例化后台统计数据接口
     * @return StatisticsForm
     */
    public function getApi()
    {
        return new StatisticsForm();
    }

    public function getStatisticsMenus($bool = true)
    {
        return [
            'is_statistics_show' => $bool,
            'name' => $bool ? $this->getDisplayName() : \Yii::t('plugins/region', '插件统计'),
            'key' => $this->getName(),
            'pic_url' => $this->getStatisticIconUrl(),
            'route' => 'mall/region-statistics/index',
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/region/mall/setting/index';
    }

    /**
     * 插件小程序端链接
     * @return array
     */
    public function getPickLink()
    {
        return [
        ];
    }

    public function getRegionForm()
    {
        return new SettingForm();
    }

    public function getSmsSetting()
    {
        return [
            'region' => [
                'title' => \Yii::t('plugins/region', '分销商成为代理提醒'),
                'content' => \Yii::t('plugins/region', '模板内容').'${name}'.\Yii::t('plugins/region', '可参与区域代理分红'),
                'tip' => \Yii::t('plugins/region', '用于区域代理'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [
                    [
                        'key' => 'name',
                        'value' => \Yii::t('plugins/region', '模板变量name'),
                        'desc' => \Yii::t('plugins/region', '例如').'${name}'.\Yii::t('plugins/region', '则需填写name')
                    ],
                ],
                'key' => 'user'
            ],
            'region_level_up' => [
                'title' => \Yii::t('plugins/region', '代理升级提醒'),
                'content' => \Yii::t('plugins/region', '您已升级为').'${name},'.\Yii::t('plugins/region', '分红比例更改为').'${number}',
                'tip' => \Yii::t('plugins/region', '用于区域代理'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [
                    [
                        'key' => 'name',
                        'value' => \Yii::t('plugins/region', '模板变量name'),
                        'desc' => \Yii::t('plugins/region', '模板内容').'${name},'.\Yii::t('plugins/region', '分红比例更改为').'${number}'.\Yii::t('plugins/region', '，则需填写name')
                    ],
                    [
                        'key' => 'number',
                        'value' => \Yii::t('plugins/region', '模板变量number'),
                        'desc' => \Yii::t('plugins/region', '模板内容').'${name},'.\Yii::t('plugins/region', '分红比例更改为').'${number}'.\Yii::t('plugins/region', '则需填写number')
                    ],
                ],
                'key' => 'user'
            ],
        ];
    }

    public function getRegionReview()
    {
        return new RegionForm();
    }

    public function getRegionApply()
    {
        return new RegionEditForm();
    }

    public function getCommonRegion()
    {
        return new Commonregion();
    }

    public function getCashConfig()
    {
        return [
            'name' => $this->getDisplayName(),
            'key' => $this->getName(),
            'class' => 'app\\plugins\\region\\models\\RegionCash',
            'user_class' => 'app\\plugins\\region\\models\\RegionUserInfo',
            'user_alias' => 'region_user'
        ];
    }

    public function needCheck()
    {
        return true;
    }

    public function needCash()
    {
        return true;
    }

    public function identityName()
    {
        return \Yii::t('plugins/region', '代理');
    }

    public function getReviewClass($config = [])
    {
        return new RegionReview($config);
    }
}
