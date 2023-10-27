<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * User: jack_guo
 * Date: 2019/7/3
 * Time: 14:12
 */

namespace app\plugins\stock;

use app\handlers\HandlerBase;
use app\plugins\stock\forms\common\CommonStock;
use app\plugins\stock\forms\common\StockReview;
use app\plugins\stock\forms\mall\StatisticsForm;
use app\plugins\stock\forms\mall\SettingForm;
use app\plugins\stock\forms\mall\StockForm;
use app\plugins\stock\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/stock', '股东分红设置'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/stock/mall/setting/index',
            ],
            [
                'name' => \Yii::t('plugins/stock', '股东管理'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/stock/mall/stock/index'
            ],
            [
                'name' => \Yii::t('plugins/stock', '股东等级'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/stock/mall/level/index',
            ],
            [
                'name' => \Yii::t('plugins/stock', '分红结算'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/stock/mall/balance/index',
                'action' => [
                    [
                        'name' => '分红结算',
                        'route' => 'plugin/stock/mall/balance/add',
                    ],
                    [
                        'name' => '分红结算',
                        'route' => 'plugin/stock/mall/balance/detail',
                    ],
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
        return 'stock';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/stock', '股东分红');
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
            'name' => $bool ? $this->getDisplayName() : \Yii::t('plugins/stock', '插件统计'),
            'key' => $this->getName(),
            'pic_url' => $this->getStatisticIconUrl(),
            'route' => 'mall/stock-statistics/index',
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/stock/mall/setting/index';
    }

    public function getStockForm()
    {
        return new SettingForm();
    }

    public function getSmsSetting()
    {
        return [
            'stock' => [
                'title' => \Yii::t('plugins/stock', '分销商成为股东提醒'),
                'content' => \Yii::t('plugins/stock', '您已成为股东'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [],
                'key' => 'user'
            ],
            'stock_level_up' => [
                'title' => \Yii::t('plugins/stock', '股东升级提醒'),
                'content' => \Yii::t('plugins/stock', '模板内容').'${name},'.\Yii::t('plugins/stock', '分红比例更改为').'${number}',
                'support_mch' => false,
                'loading' => false,
                'variable' => [
                    [
                        'key' => 'name',
                        'value' => \Yii::t('plugins/stock', '模板变量name'),
                        'desc' => \Yii::t('plugins/stock', '模板内容').'${name},'.\Yii::t('plugins/stock', '分红比例更改为').'${number}'.\Yii::t('plugins/stock', '则需填写name')
                    ],
                    [
                        'key' => 'number',
                        'value' => \Yii::t('plugins/stock', '模板变量number'),
                        'desc' => \Yii::t('plugins/stock', '模板内容').'${name},'.\Yii::t('plugins/stock', '分红比例更改为').'${number}'.\Yii::t('plugins/stock', '则需填写name')
                    ],
                ],
                'key' => 'user'
            ],
        ];
    }

    public function getStockReview()
    {
        return new StockForm();
    }

    public function getStockApply()
    {
        return new CommonStock();
    }

    public function getCommonStock()
    {
        return new CommonStock();
    }

    public function getCashConfig()
    {
        return [
            'name' => $this->getDisplayName(),
            'key' => $this->getName(),
            'class' => 'app\\plugins\\stock\\models\\StockCash',
            'user_class' => 'app\\plugins\\stock\\models\\StockUserInfo',
            'user_alias' => 'stock_user'
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
        return \Yii::t('plugins/stock', '股东');
    }

    public function getReviewClass($config = [])
    {
        return new StockReview($config);
    }
}
