<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/15
 * Time: 17:01
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\assistant;


class Plugin extends \app\plugins\Plugin
{
    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * 采集助手
     * @return string
     */
    public function getName()
    {
        return 'assistant';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/assistant', '采集助手');
    }

    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/assistant', '基本配置'),
                'route' => 'plugin/assistant/mall/index/index',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/assistant', '采集商品'),
                'route' => 'plugin/assistant/mall/index/collect',
                'icon' => 'el-icon-star-on',
            ],
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/assistant/mall/index/collect';
    }
}
