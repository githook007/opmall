<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common;

use app\forms\mall\copyright\CopyrightForm;
use app\forms\mall\home_page\HomePageForm;
use app\forms\mall\navbar\NavbarForm;
use app\forms\mall\poster\PosterForm;
use app\forms\mall\recharge\RechargeSettingForm;
use app\forms\mall\sms\SmsForm;
use app\forms\PickLinkForm;
use app\models\GoodsCats;
use app\models\HomeBlock;
use app\models\Option;
use yii\helpers\ArrayHelper;

class CommonAppConfig
{
    /**
     * 底部导航设置
     * @return null
     */
    public static function getNavbar()
    {
        $option = CommonOption::get(
            Option::NAME_NAVBAR,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            (new NavbarForm())->getDefault()
        );

        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        $permissionFlip = array_flip($permission);
        //todo 临时解决底部导航问题
        $is_live = true;
        $is_pick = true;
        if (!in_array('live', $permission)) {
            $is_live = false;
        }

        if (!in_array('pick', $permission)) {
            $is_pick = false;
        }

        foreach ($option['navs'] as $key => $nav) {
            if (isset($nav['params']) && $nav['open_type'] != "h5") { // @czs
                $urlList = explode('?', $nav['url']);
                $url = $urlList[0] . '?';
                foreach ($nav['params'] as $item) {
                    if ($item['value'] !== '') {
                        $url .= $item['key'] . '=' . ($nav['open_type'] == 'web' ? urlencode($item['value']) : $item['value']) . '&';
                    }
                }
                $option['navs'][$key]['url'] = substr($url, 0, -1);
            }

            // TODO 小程序插件权限统一处理
            if (isset($nav['key']) && $nav['key'] && !isset($permissionFlip[$nav['key']])) {
                unset($option['navs'][$key]);
            }

            $check = strpos($nav['url'], 'wx2b03c6e691cd7370') !== false;
            if (($nav['url'] == '/pages/live/index' || $check) && !$is_live) {
                unset($option['navs'][$key]);
            }
            if (($nav['url'] == '/plugins/pick/index/index') && !$is_pick) {
                unset($option['navs'][$key]);
            }
        }
        $option['navs'] = array_values($option['navs']);

        if (gettype($option['shadow']) === 'string') {
            $option['shadow'] = json_decode($option['shadow']);
        }

        if (gettype($option['is_eject']) === 'string') {
            $option['is_eject'] = json_decode($option['is_eject']);
        }

        return $option;
    }

    /**
     * @param false $isAdmin
     * 商城版权设置
     * @return null
     */
    public static function getCoryRight($isAdmin = false)
    {
        $mallId = $isAdmin ? 0 : \Yii::$app->mall->id;
        $option = CommonOption::get(
            Option::NAME_COPYRIGHT,
            $mallId,
            Option::GROUP_APP
        );
        $default = (new CopyrightForm())->getDefault();
        $option = self::check($option, $default);

        // TODO 兼容 2019-6-24
        if (!isset($option['link'])) {
            $option['params'] = [];
            $option['link'] = [];
        }

        return $option;
    }

    /**
     * @param $mchId
     * @return null
     */
    public static function getSmsConfig($mchId = null)
    {
        if ($mchId === null || $mchId === '') {
            $isGuest = true;
            try {
                $isGuest = \Yii::$app->user->isGuest;
            } catch (\Exception $exception) {
            }
            if (!$isGuest) {
                $mchId = \Yii::$app->user->identity->mch_id;
            } else {
                $mchId = 0;
            }
        }
        $option = CommonOption::get(
            Option::NAME_SMS,
            \Yii::$app->mall->id,
            Option::GROUP_ADMIN,
            null,
            $mchId
        );
        $default = (new SmsForm())->getDefault();
        $option = self::check($option, $default);

        return $option;
    }

    /**
     * 商城海报设置
     * @return null
     */
    public static function getPosterConfig()
    {
        $option = CommonOption::get(
            Option::NAME_POSTER,
            \Yii::$app->mall->id,
            Option::GROUP_APP
        );
        $default = (new PosterForm())->getDefault();
        $option = self::check($option, $default);

        return $option;
    }

    /**
     * 已存储数据和默认数据对比，以默认数据字段为准
     * @param $list
     * @param $default
     * @return mixed
     */
    public static function check($list, $default)
    {
        foreach ($default as $key => $value) {
            if (!isset($list[$key])) {
                $list[$key] = $value;
                continue;
            }
            if (is_array($value)) {
                $list[$key] = self::check($list[$key], $value);
            }
        }
        return $list;
    }

    /**
     * 小程序首页配置
     * @return null
     */
    public static function getHomePageConfig()
    {
        $option = CommonOption::get(
            Option::NAME_HOME_PAGE,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            (new HomePageForm())->getDefault()
        );

        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        $permissionFlip = array_flip($permission);
        $catList = [];
        $cat = [];
        $blockList = [];
        $block = [];
        foreach ($option as $item) {
            switch ($item['key']) {
                case 'cat':
                    if ($item['relation_id'] > 0) {
                        $catList[] = $item['relation_id'];
                    }
                    break;
                case 'block':
                    if ($item['relation_id'] > 0) {
                        $blockList[] = $item['relation_id'];
                    }
                    break;
                default:
            }
        }
        if (!empty($catList)) {
            $cat = GoodsCats::find()->where([
                'is_delete' => 0,
                'id' => $catList,
                'mall_id' => \Yii::$app->mall->id,
                'mch_id' => 0,
            ])->select(['id'])->column();
        }
        if (!empty($blockList)) {
            $block = HomeBlock::find()->where([
                'is_delete' => 0,
                'id' => $blockList,
                'mall_id' => \Yii::$app->mall->id,
            ])->select(['id'])->column();
        }

        // 排除分类 魔方已被删除的数据
        foreach ($option as $key => $item) {
            // 小程序端插件权限统一处理
            if (isset($item['permission_key'])
                && $item['permission_key']
                && !isset($permissionFlip[$item['permission_key']])
            ) {
                unset($option[$key]);
                continue;
            }

            // 移除被删除的分类
            if ($item['key'] == 'cat' && $item['relation_id'] > 0 && !in_array($item['relation_id'], $cat)) {
                unset($option[$key]);
                continue;
            }
            // 移除被删除的魔方
            if ($item['key'] == 'block' && $item['relation_id'] > 0 && !in_array($item['relation_id'], $block)) {
                unset($option[$key]);
                continue;
            }

            $baseUri = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
            if ($item['key'] == 'coupon') {
                $option[$key]['coupon_url'] = $item['coupon_url'] ?: $baseUri . '/statics/img/mall/home_block/coupon-open.png';
                $option[$key]['coupon_not_url'] = $item['coupon_not_url'] ?: $baseUri . '/statics/img/mall/home_block/coupon-close.png';
                $option[$key]['discount_not_url'] = isset($item['discount_not_url']) && $item['discount_not_url'] ? $item['discount_not_url'] : $baseUri . '/statics/img/mall/home_block/discount-bg.png';
            }
        }
        $arr = ArrayHelper::toArray($option);

        return array_values($arr);
    }

    /**
     * 小程序充值
     * @return null
     */
    public static function getRechargeSetting()
    {
        $iconUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
            '/statics/img/common/';

        $option = CommonOption::get(
            Option::NAME_RECHARGE_SETTING,
            \Yii::$app->mall->id,
            Option::GROUP_APP
        );
        if ($option) {
            $option['bj_pic_url'] = $option['bj_pic_url'] ?: [
                'url' => $iconUrlPrefix . 'balance-bg.png'
            ];
            $option['ad_pic_url'] = $option['ad_pic_url'] ?: [
                'url' => $iconUrlPrefix . 'balance-ad.png'
            ];
            $option['re_pic_url'] = $option['re_pic_url'] ?: [
                'url' => $iconUrlPrefix . 'balance-icon.png'
            ];
        }
        $default = (new RechargeSettingForm())->getDefault();
        $option = self::check($option, $default);

        return $option;
    }

    /**
     * @return array
     * 分类样式
     */
    public static function getAppCatStyle($mch_id = 0)
    {
        $option = CommonOption::get(
            Option::NAME_CAT_STYLE_SETTING,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            [],
            $mch_id
        );

        $default = [
            'cat_style' => '3',
            'cat_goods_count' => '1',
            'cat_goods_cols' => '1'
        ];
        $option = self::check($option, $default);

        return $option;
    }

    // 获取小程序自定义标题
    public static function getBarTitle()
    {
        $option = CommonOption::get(Option::NAME_PAGE_TITLE, \Yii::$app->mall->id, Option::GROUP_APP);

        $newOption = [];
        if ($option) {
            foreach ($option as $item) {
                $newOption[$item['name']] = $item;
            }
        }

        $default = PickLinkForm::getCommon()->getTitle();
        foreach ($default as $key => $item) {
            if ($item['value'] == '/pages/index/index') {
                unset($default[$key]);
            }
        }
        $default = array_values($default);
        foreach ($default as &$item) {
            if (isset($newOption[$item['name']])) {
                $item['new_name'] = $newOption[$item['name']]['new_name'];
            }
        }
        unset($item);

        return $default;
    }

    /**
     * @return array
     * 获取所有页面的默认配置（暂时只有授权页面）
     */
    public static function getDefaultPageList()
    {
        $picUrl = (\Yii::$app->hostInfo ?: \Yii::$app->request->hostInfo)
            . (\Yii::$app->baseUrl ?: \Yii::$app->request->baseUrl)
            . '/statics/img/app/mall';
        return [
            'auth' => [
                'pic_url' => $picUrl . '/auth-default.png',
                'hotspot' => [
                    'width' => '528',
                    'height' => '81',
                    'left' => '61',
                    'top' => '419',
                    'defaultX' => '61',
                    'defaultY' => '419',
                    'link' => '',
                    'open_type' => 'login'
                ],
                'hotspot_cancel' => [
                    'width' => '528',
                    'height' => '81',
                    'left' => '61',
                    'top' => '509',
                    'defaultX' => '61',
                    'defaultY' => '509',
                    'link' => '',
                    'open_type' => 'cancel'
                ]
            ]
        ];
    }
}
