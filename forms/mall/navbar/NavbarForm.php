<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\navbar;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class NavbarForm extends Model
{
    public function getDetail()
    {
        $navbar = CommonAppConfig::getNavbar();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $navbar,
            ]
        ];
    }

    public function restoreDefault()
    {
        $res = CommonOption::set(Option::NAME_NAVBAR, $this->getDefault(), \Yii::$app->mall->id, Option::GROUP_APP);

        if (!$res) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '恢复失败',
            ];
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '恢复成功',
        ];
    }

    public function getDefault()
    {
        $iconUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
            '/statics/img/mall/navbar/';
        return [
            'bottom_background_color' => '#FFFFFF',
            'top_background_color' => '#000000',
            'top_text_color' => '#ffffff',
            'shadow' => true,
            'is_eject' => false,
            'eject_num' => 1,
            'navs' => [
                [
                    'active_color' => '#4a53ff',
                    'active_icon' => $iconUrlPrefix . 'nav-icon-index.active.png?refresh',
                    'color' => '#888',
                    'text' => '首页',
                    'icon' => $iconUrlPrefix . 'nav-icon-index.png',
                    'url' => '/pages/index/index',
                    'open_type' => 'redirect',
                ],
                [
                    'active_color' => '#4a53ff',
                    'active_icon' => $iconUrlPrefix . 'nav-icon-cat.active.png?refresh',
                    'color' => '#888',
                    'text' => '分类',
                    'icon' => $iconUrlPrefix . 'nav-icon-cat.png',
                    'url' => '/pages/cats/cats',
                    'open_type' => 'redirect',
                ],
                [
                    'active_color' => '#4a53ff',
                    'active_icon' => $iconUrlPrefix . 'nav-icon-cart.active.png?refresh',
                    'color' => '#888',
                    'text' => '购物车',
                    'icon' => $iconUrlPrefix . 'nav-icon-cart.png',
                    'url' => '/pages/cart/cart',
                    'open_type' => 'redirect',
                ],
                [
                    'active_color' => '#4a53ff',
                    'active_icon' => $iconUrlPrefix . 'nav-icon-user.active.png?refresh',
                    'color' => '#888',
                    'text' => '我',
                    'icon' => $iconUrlPrefix . 'nav-icon-user.png',
                    'url' => '/pages/user-center/user-center',
                    'open_type' => 'redirect',
                ],
            ]
        ];
    }
}
