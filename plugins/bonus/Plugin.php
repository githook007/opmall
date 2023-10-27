<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/3
 * Time: 14:12
 */

namespace app\plugins\bonus;

use app\handlers\HandlerBase;
use app\plugins\bonus\forms\common\BonusReview;
use app\plugins\bonus\forms\common\CommonCaptain;
use app\plugins\bonus\forms\mall\CaptainForm;
use app\plugins\bonus\forms\mall\OrderBonusForm;
use app\plugins\bonus\forms\mall\SettingForm;
use app\plugins\bonus\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/bonus', '队长管理'),
                'route' => 'plugin/bonus/mall/captain/index',
                'icon' => 'el-icon-star-on',
                'action' => [
                    [
                        'name' => '订单详情',
                        'route' => 'plugin/bonus/mall/captain/detail',
                    ]
                ]
            ],
            [
                'name' => \Yii::t('plugins/bonus', '队长等级设置'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/bonus/mall/members/index',
                'action' => [
                    [
                        'name' => '新增/编辑等级',
                        'route' => 'plugin/bonus/mall/members/edit',
                    ]
                ]
            ],
            [
                'name' => \Yii::t('plugins/bonus', '分红订单'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/bonus/mall/order/index',
                'action' => [
                    [
                        'name' => '订单详情',
                        'route' => 'plugin/bonus/mall/order/detail',
                    ]
                ]
            ],
            [
                'name' => \Yii::t('plugins/bonus', '设置'),
                'icon' => 'el-icon-star-on',
                'route' => 'plugin/bonus/mall/setting/index',
            ],
            // [
            //     'name' => '消息通知',
            //     'icon' => 'el-icon-star-on',
            //     'route' => 'plugin/bonus/mall/setting/template',
            // ],
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
        return 'bonus';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/bonus', '团队分红');
    }

    public function getAppConfig()
    {
        return [];
    }

    public function getIndexRoute()
    {
        return 'plugin/bonus/mall/captain/index';
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

    public function getSmsSetting()
    {
        return [
            'bonus' => [
                'title' => \Yii::t('plugins/bonus', '分销商成为队长提醒'),
                'content' => \Yii::t('plugins/bonus', 'a10').'${name}'.\Yii::t('plugins/bonus', 'a11'),
                'support_mch' => false,
                'loading' => false,
                'variable' => [
                    [
                        'key' => 'name',
                        'value' => \Yii::t('plugins/bonus', 'a15'),
                        'desc' => \Yii::t('plugins/bonus', 'a12').': "'.\Yii::t('plugins/bonus', 'a13').'${name}'.\Yii::t('plugins/bonus', 'a11').'"'.\Yii::t('plugins/bonus', 'a14')
                    ],
                ],
                'key' => 'user'
            ],
        ];
    }

    public function getBonusForm()
    {
        return new SettingForm();
    }

    public function getBonusReview()
    {
        return new CaptainForm();
    }

    public function getBonusApply()
    {
        return new CommonCaptain();
    }

    public function getCashConfig()
    {
        return [
            'name' => $this->getDisplayName(),
            'key' => $this->getName(),
            'class' => 'app\\plugins\\bonus\\models\\BonusCash',
            'user_class' => 'app\\plugins\\bonus\\models\\BonusCaptain',
            'user_alias' => 'bonus_user'
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
        return \Yii::t('plugins/bonus', '队长');
    }

    public function getReviewClass($config = [])
    {
        return new BonusReview($config);
    }

    public function setBonusOrderLog($order)
    {
        \Yii::error('礼物订单');
        \Yii::error($order);
        //分红完成
        $form = new OrderBonusForm();
        $form->order = $order;
        $form->bonusOver();
    }
}
