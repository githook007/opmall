<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */

namespace app\plugins\clerk;

use app\forms\PickLinkForm;
use app\helpers\PluginHelper;
use app\models\ClerkUser;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('common', '核销中心'),
                'route' => 'plugin/clerk/mall/index/index',
                'icon' => 'el-icon-star-on',
                'is_jump' => 0
            ]
        ];
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'clerk';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('common', '核销员');
    }

    public function getAppConfig()
    {
        return [];
    }

    /**
     * 插件小程序端链接
     * @return array
     */
    public function getPickLink()
    {
        $iconBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img/pick-link';

        return [
            [
                'key' => 'clerk',
                'name' => \Yii::t('common', '核销中心'),
                'open_type' => 'navigate',
                'icon' => $iconBaseUrl . '/icon-clerk.png',
                'value' => '/plugins/clerk/index/index',
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
            ],
        ];
    }

    public function getSpecialNotSupport()
    {
        $path = '/plugins/clerk/index/index';
        $res = [];
        if (\Yii::$app->user->isGuest) {
            $res = [
                'user_center' => [
                    $path
                ]
            ];
        } else {
            $clerkUser = ClerkUser::findOne([
                'user_id' => \Yii::$app->user->id, 'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
            ]);
            if (!$clerkUser) {
                $res = [
                    'user_center' => [
                        $path
                    ]
                ];
            }
        }
        return $res;
    }
}
