<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\Plugin;

class PickLinkForm extends Model
{
    const OPEN_TYPE_1 = 'redirect';
    const OPEN_TYPE_2 = 'navigate';
    const OPEN_TYPE_3 = 'app';
    const OPEN_TYPE_4 = 'contact';
    const OPEN_TYPE_5 = 'clear_cache';
    const OPEN_TYPE_6 = 'tel';
    const OPEN_TYPE_7 = 'video_number';

    // 忽略的场景
    const IGNORE_TITLE = 'title'; // 页面标题
    const IGNORE_NAVIGATE = 'navigate'; // 导航底栏
    // 适用场景
    const ONLY_PAGE = 'app_page'; // 页面
    // 使用场景
    const USE_COPYRIGHT = 'copyright'; // 版权

    public $ignore;
    public $only;
    public $use;
    public $keyword;  // 搜索

    /**
     * 小程序菜单跳转链接
     * @param $links
     * @return mixed|string
     */
    public function getList($links)
    {
        $list = [];
        $id = 1;
        foreach ($links as $item) {
            $item['id'] = $id++;
            $list[] = $item;
        }

        $newList = [];
        foreach ($list as $item) {
            if ($this->ignore && isset($item['ignore']) && in_array($this->ignore, $item['ignore'])) {
                continue;
            }
            if (isset($item['only']) && !in_array($this->only, $item['only'])) {
                continue;
            }
            if ($this->use && (isset($item['use']) && !in_array($this->use, $item['use']) || !isset($item['use']))) {
                continue;
            }
            if($this->keyword){
                if(substr_count($item['name'], $this->keyword) < 1){
                    continue;
                }
            }
            if (isset($item['type']) && $item['type'] === 'base') {
                $newList['base'][] = $item;
            } elseif (isset($item['type']) && $item['type'] === 'marketing') {
                $newList['marketing'][] = $item;
            } elseif (isset($item['type']) && $item['type'] === 'order') {
                $newList['order'][] = $item;
            } elseif (isset($item['type']) && $item['type'] === 'diy') {
                $newList['diy'][] = $item;
            } else {
                $newList['plugin'][] = $item;
            }
        }

        return $newList;
    }

    /**
     * 导航链接
     * @return array
     * [
     * type: 所属的标签base--基础|marketing--营销|plugin--插件|order--订单|diy--diy
     * name: 链接名称
     * open_type: 链接操作方式 OPEN_TYPE_1--跳转|OPEN_TYPE_2--重定向|OPEN_TYPE_3--跳转小程序|OPEN_TYPE_4--客服
     *            |OPEN_TYPE_5--清除缓存|OPEN_TYPE_6--拨号
     * icon: 链接图标
     * value: 链接的路径
     * params: 链接上的参数
     * key: 链接的权限
     * ignore: 链接忽略场景 IGNORE_TITLE--页面标题|IGNORE_NAVIGATE--导航底栏
     * only: 链接只只用于这些场景 ONLY_PAGE--页面
     * ]
     */
    private function links()
    {
        $iconUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
            '/statics/img/mall/pick-link/';

        return [
            [
                'type' => 'base',
                'name' => \Yii::t('common', '商城首页'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-index.png',
                'value' => '/pages/index/index',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '分销中心'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-share-center.png',
                'value' => '/pages/share/index/index',
                'key' => 'share',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '我的卡券'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-user-card.png',
                'value' => '/pages/card/index/index',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '我的优惠券'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-user-coupons.png',
                'value' => '/pages/coupon/index/index',
                'key' => 'coupon',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '领券中心'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-coupons.png',
                'value' => '/pages/coupon/list/list',
                'key' => 'coupon',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '我的收藏'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-favorite.png',
                'value' => '/pages/favorite/favorite',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '积分明细'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-integral.png',
                'value' => '/pages/user-center/integral-detail/integral-detail',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '联系我们'),
                'open_type' => self::OPEN_TYPE_6,
                'icon' => $iconUrlPrefix . 'icon-contact.png',
                'value' => 'tel',
                'params' => [
                    [
                        'key' => 'tel',
                        'value' => '',
                        'desc' => \Yii::t('common', '填联系电话'),
                        'is_required' => true,
                        'data_type' => 'text'
                    ]
                ],
                'ignore' => [self::IGNORE_TITLE]
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '文章中心'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-article.png',
                'value' => '/pages/article/article-list/article-list',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '文章详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-article-detail.png',
                'value' => '/pages/article/article-detail/article-detail',
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => \Yii::t('common', '填文章ID'),
                        'is_required' => true,
                        'data_type' => 'number',
                        'page_url' => 'mall/article/index',
                        'page_url_text' => \Yii::t('common', '文章位置')
                    ]
                ]
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '收货地址'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-address.png',
                'value' => '/pages/address/address',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '绑定手机号'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-user-bangding.png',
                'value' => '/pages/binding/binding',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '余额支付密码'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-password.png',
                'value' => '/pages/balance/password',
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '我的订单'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order.png',
                'value' => '/pages/order/index/index',
                'params' => [
                    [
                        'key' => 'status',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b18'),
                        'is_required' => false,
                        'data_type' => 'number'
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '全部订单'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-all.png',
                'value' => '/pages/order/index/index?status=0',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '待付款'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-0.png',
                'value' => '/pages/order/index/index?status=1',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '待发货'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-1.png',
                'value' => '/pages/order/index/index?status=2',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '待收货'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-2.png',
                'value' => '/pages/order/index/index?status=3',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '待评价'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-3.png',
                'value' => '/pages/order/index/index?status=4',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'order',
                'name' => \Yii::t('common', '售后'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-order-4.png',
                'value' => '/pages/order/index/index?status=5',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '清除缓存'),
                'open_type' => self::OPEN_TYPE_5,
                'icon' => $iconUrlPrefix . 'icon-clear-cache.png',
                'value' => self::OPEN_TYPE_5,
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '购物车'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-cart.png',
                'value' => '/pages/cart/cart',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '分类'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-cats.png',
                'value' => '/pages/cats/cats',
                'params' => [
                    [
                        'key' => 'cat_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b28'),
                        'is_required' => false,
                        'data_type' => 'number',
                        'page_url' => 'mall/cat/index',
                        'page_url_text' => \Yii::t('common', 'b29')
                    ]
                ]
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '会员中心'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-member-center.png',
                'value' => '/pages/member/index/index',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '用户中心'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-user-center.png',
                'value' => '/pages/user-center/user-center',
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '商品列表'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-goods.png',
                'value' => '/pages/goods/list',
                'params' => [
                    [
                        'key' => 'cat_id',
                        'value' => "",
                        'desc' => \Yii::t('common', 'b28'),
                        'is_required' => false,
                        'data_type' => 'number',
                        'page_url' => 'mall/cat/index',
                        'page_url_text' => \Yii::t('common', 'b29')
                    ]
                ]
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '商品详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-goods-detail.png',
                'value' => '/pages/goods/goods',
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b34'),
                        'is_required' => true,
                        'data_type' => 'number',
                        'page_url' => 'mall/goods/index',
                        'page_url_text' => \Yii::t('common', 'b35')
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_TITLE, PickLinkForm::IGNORE_NAVIGATE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '专题列表'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-topic.png',
                'value' => '/pages/topic/list',
                'params' => [
                    [
                        'key' => 'type',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b37'),
                        'is_required' => false,
                        'data_type' => 'number',
                        'page_url' => 'mall/topic-type/index',
                        'page_url_text' => \Yii::t('common', 'b38')
                    ]
                ],
                'key' => 'topic',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '专题详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-topic-detail.png',
                'value' => '/pages/topic/topic',
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b40'),
                        'is_required' => true,
                        'data_type' => 'number',
                        'page_url' => 'mall/topic/index',
                        'page_url_text' => \Yii::t('common', 'b41')
                    ]
                ],
                'key' => 'topic',
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '跳转小程序'),
                'open_type' => self::OPEN_TYPE_3,
                'icon' => $iconUrlPrefix . 'icon-mini.png',
                'value' => self::OPEN_TYPE_3,
                'remark' => \Yii::t('common', 'b44'),
                'params' => [
                    [
                        'key' => 'username',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b45'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'we_path',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b46'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'app_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b47'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'path',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b48'),
                        'is_required' => false
                    ],

                    [
                        'key' => 'ali_app_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b49'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'ali_path',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b50'),
                        'is_required' => false
                    ],

                    [
                        'key' => 'tt_app_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b51'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'tt_path',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b52'),
                        'is_required' => false
                    ],

                    [
                        'key' => 'bd_app_key',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b53'),
                        'is_required' => false
                    ],
                    [
                        'key' => 'bd_path',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b54'),
                        'is_required' => false
                    ],
                ],
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '网页链接'),
                'open_type' => 'web',
                'icon' => $iconUrlPrefix . 'icon-web-page.png',
                'value' => '/pages/web/web',
                'params' => [
                    [
                        'key' => 'url',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b56'),
                        'is_required' => true
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '门店列表'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-store.png',
                'value' => '/pages/store/store',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '门店详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-store.png',
                'value' => '/pages/store/detail',
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b59'),
                        'is_required' => true,
                        'page_url' => 'mall/store/index',
                        'page_url_text' => \Yii::t('common', 'b60')
                    ]
                ],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '快速购买'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-quick-shop.png',
                'value' => '/pages/quick-shop/quick-shop',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '充值中心'),
                'new_name' => '',
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-recharge.png',
                'value' => '/pages/balance/recharge',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '余额记录'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-recharge-log.png',
                'value' => '/pages/balance/balance',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '搜索页'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-search.png',
                'value' => '/pages/search/search',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '客服'),
                'open_type' => self::OPEN_TYPE_4,
                'icon' => $iconUrlPrefix . 'icon-service.png',
                'value' => self::OPEN_TYPE_4,
                'ignore' => [self::IGNORE_TITLE]
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '视频专区'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-video.png',
                'value' => '/pages/video/video',
                'key' => 'video',
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', 'H5跳转地址'),
                'open_type' => 'h5',
                'icon' => $iconUrlPrefix . 'icon-web-link.png',
                'value' => '',
                'original' => true,
                'params' => [
                    [
                        'key' => 'url',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b69'),
                        'is_required' => true
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_TITLE],
            ], // @czs
            [
                'type' => 'base',
                'name' => \Yii::t('common', '我的足迹'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-foot.png',
                'value' => '/pages/foot/index/index',
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
            ],
            [
                'type' => 'base',
                'name' => \Yii::t('common', '账单总结'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-summary.png',
                'value' => '/pages/foot/summary/summary',
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '优惠券详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-user-coupons-detail.png',
                'value' => '/pages/coupon/details/details',
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b73'),
                        'is_required' => true,
                        'data_type' => 'number',
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
                'key' => 'coupon',
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '分销商专属链接'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-summary.png',
                'value' => '/pages/index/index',
                'params' => [
                    [
                        'key' => 'user_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b75'),
                        'is_required' => true,
                        'data_type' => 'number',
                    ]
                ],
                'ignore' => [PickLinkForm::IGNORE_NAVIGATE],
                'only' => [PickLinkForm::ONLY_PAGE],
            ],
            [
                'type' => 'marketing',
                'key' => 'live',
                'name' => \Yii::t('common', '直播列表'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-live.png',
                'value' => '/pages/live/index',
            ],
            [
                'type' => 'marketing',
                'key' => 'live',
                'name' => \Yii::t('common', '直播详情'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-live-detail.png',
                'value' => 'plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin',
                'params' => [
                    [
                        'key' => 'room_id',
                        'value' => '',
                        'desc' => \Yii::t('common', 'b78'),
                        'is_required' => true,
                        'data_type' => 'number',
                    ]
                ],
                'ignore' => [PickLinkForm::ONLY_PAGE, PickLinkForm::IGNORE_TITLE],
            ],
            [
                'type' => 'marketing',
                'name' => \Yii::t('common', '满减优惠'),
                'open_type' => '',
                'icon' => $iconUrlPrefix . 'icon-full-reduce.png',
                'value' => '/pages/full_reduce/index/index',
            ],
        ];
    }

    public function checkPickLink($list)
    {
        $newList = [];
        try {
            if (\Yii::$app->appPlatform != 'webapp') {
                throw new \Exception('小程序端');
            }
            foreach ($list as $item) {
                if (\Yii::$app->role->checkLink($item)) {
                    $newList[] = $item;
                }
            }

            $plugins = \Yii::$app->role->getPluginList();
            foreach ($plugins as $plugin) {
                if (method_exists($plugin, 'getPickLink')) {
                    $newList = array_merge($newList, $plugin->getPickLink());
                }
            }
        } catch (\Exception $exception) {
            $newList = $list;
            $plugins = \Yii::$app->plugin->list;
            foreach ($plugins as $plugin) {
                $PluginClass = 'app\\plugins\\' . $plugin->name . '\\Plugin';
                /** @var Plugin $pluginObject */
                if (!class_exists($PluginClass)) {
                    continue;
                }
                $object = new $PluginClass();
                if (method_exists($object, 'getPickLink')) {
                    $newList = array_merge($newList, $object->getPickLink());
                }
            }
        }

        foreach ($newList as &$item) {
            if (!$item['open_type']) {
                $item['open_type'] = 'navigate';
            }
        }
        unset($item);

        return $newList;
    }

    /**
     * @return array
     * 小程序页面链接（去掉一部分分页面链接）
     */
    public function appPage()
    {
        $list = $this->links();
        $list = $this->checkPickLink($list);
        foreach ($list as $index => $item) {
            if (!($item['open_type'] == '' || $item['open_type'] == 'navigate' || $item['open_type'] == 'redirect')) {
                unset($list[$index]);
            }
        }
        $list = array_values($list);
        $list = $this->getList($list);
        $pluginList = \Yii::$app->mall->role->getPluginList();
        $webUri = [];
        foreach ($pluginList as $plugin) {
            if (method_exists($plugin, 'getWebUri')) {
                $webUri[$plugin->getName()] = rtrim($plugin->getWebUri(), '/');
            }
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'webUri' => $webUri
            ]
        ];
    }

    /**
     * @return array
     * 跳转链接
     */
    public function getLink()
    {
        $list = $this->links();
        $list = $this->checkPickLink($list);
        $list = $this->getList($list);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'name' => array_keys($list)[0] ?? ''
            ]
        ];
    }


    /**
     * @return array
     * 小程序端页面标题
     */
    public function getTitle()
    {
        $res = $this->links();
        $res = $this->checkPickLink($res);

        $newList = [];
        foreach ($res as $item) {
            // 删除不需要在标题中显示的内容
            if (isset($item['ignore']) && in_array('title', $item['ignore'])) {
                continue;
            }
            if ($item['value']) {
                $newList[] = [
                    'name' => $item['name'],
                    'value' => $item['value'],
                    'new_name' => $item['new_name'] ?? $item['name'],
                ];
            }
        }

        return $newList;
    }

    public static function getCommon()
    {
        return new self();
    }

    public function getAdminCopyrightLink()
    {
        $list = $this->links();

        $newList = [
            'base' => [],
            'marketing' => [],
            'order' => [],
            'diy' => [],
            'plugin' => [],
        ];

        foreach ($list as $key => $value) {
            if (in_array($value['name'], ['联系我们', '网页链接', '跳转小程序'])) {
                $newList['base'][] = $value;
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $newList,
            ]
        ];
    }
}
