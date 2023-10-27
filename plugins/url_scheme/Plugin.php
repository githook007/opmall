<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: wxf
 */

namespace app\plugins\url_scheme;

use app\helpers\PluginHelper;
use app\models\Mall;
use app\plugins\url_scheme\models\UrlScheme;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/url_scheme', '链接生成工具'),
                'route' => 'plugin/url_scheme/mall/index',
                'icon' => 'el-icon-star-on',
            ]
        ];
    }

    public function handler()
    {

    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'url_scheme';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/url_scheme', '微信').\Yii::t('plugins/url_scheme', '链接生成工具');
    }

    public function getAppConfig()
    {
        $imageBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/image';
        return [
            'app_image' => [
                'banner_image' => $imageBaseUrl . '/banner.jpg',
                'fxhb_none' => $imageBaseUrl . '/fxhb_none.png',
                'bg' => $imageBaseUrl . '/bg.png',
                'share_modal_bg' => $imageBaseUrl . '/share_modal_bg.png',
                'hongbao_bg' => $imageBaseUrl . '/hongbao_bg.png',
            ],
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/url_scheme/mall/index';
    }

    /**
     * 插件小程序端链接
     * @return array
     */
    public function getPickLink()
    {
        return [];
    }

    public function getHomePage($type)
    {

    }

    public function getStatisticsMenus($bool = true)
    {
        return [];
    }

    public function getSchemeUrl($id){
        $model = UrlScheme::findOne($id);
        if ($model) {
            \Yii::$app->setMall(Mall::findOne($model->mall_id));
            $params = [
                'jump_wxa' => (array)\Yii::$app->serializer->decode($model->link),
                'expire_type' => $model->is_expire
            ];
            if($model->is_expire == 1){
                if(strtotime($model->created_at) + $model->expire_time * 86400 < time()){
                    return '';
                }
                $params['expire_interval'] = $model->expire_time;
            }
            /** @var \app\plugins\wxapp\Plugin $plugin */
            $plugin = \Yii::$app->plugin->getPlugin('wxapp');
            $res = $plugin->getSubscribe()->getScheme($params);
            return $res['openlink'];
        }else{
            return '';
        }
    }
}
