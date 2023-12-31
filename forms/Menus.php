<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms;

use app\plugins\Plugin;

class Menus
{
    /**
     * 只允许 超级管理员 访问的商城路由KEY
     */
    const MALL_SUPER_ADMIN_KEY = [
        'course_setting',
        'permission_manage',
        'app_manage',
        'upload_admin',
        'add_account',
        'register_audit',
        'system_setting',
        'db_manage',
        'account_list',
        'overrun',
        'base_setting',
        'queue_service',
        'super-notice',
        'message_remind',
        'wxapp',
        'mall_notice',
    ];

    /**
     * 只允许 多商户 访问的路由KEY
     */
    const MALL_MCH_KEY = [
        'mall/mch/setting',
        'mall/mch/manage',
        'mall/mch/account-log',
        'mall/mch/cash-log',
        'mall/mch/order-close-log',
        'mall/assistant/index',
        'mall/assistant/collect',
        'mall/mch/share-order'
    ];

    /**
     * 商城主菜单
     * @param $isPluginMenus
     * @return array
     */
    public static function getMallMenus($isPluginMenus = false)
    {
        $mallMenus = [
            [
                'name' => \Yii::t('common', '数据统计'),
                'route' => '',
                'key' => 'statistics',
                'icon' => 'statics/img/mall/nav/statics.png',
                'icon_active' => 'statics/img/mall/nav/statics-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '数据概况'),
                        'route' => 'mall/data-statistics/index',
                        'action' => [
                            [
                                'name' => '公告',
                                'route' => 'mall/notice/detail',
                            ],
                            [
                                'name' => '商品购买力TOP排行',
                                'route' => 'mall/data-statistics/goods_top',
                            ],
                            [
                                'name' => '用户购买力TOP排行',
                                'route' => 'mall/data-statistics/users_top',
                            ],
                            [
                                'name' => '砍价统计',
                                'route' => 'mall/bargain-statistics/index'
                            ],
                            [
                                'name' => '预约统计',
                                'route' => 'mall/booking-statistics/index'
                            ],
                            [
                                'name' => '社区团购统计',
                                'route' => 'mall/community-statistics/index'
                            ],
                            [
                                'name' => '套餐组合统计',
                                'route' => 'mall/composition-statistics/index'
                            ],
                            [
                                'name' => '限时抢购统计',
                                'route' => 'mall/flash-sale-statistics/index'
                            ],
                            [
                                'name' => '社交送礼统计',
                                'route' => 'mall/gift-statistics/index'
                            ],
                            [
                                'name' => '积分商城统计',
                                'route' => 'mall/integral-statistics/mall'
                            ],
                            [
                                'name' => '幸运抽奖统计',
                                'route' => 'mall/lottery-statistics/index'
                            ],
                            [
                                'name' => '多商户统计',
                                'route' => 'mall/order-statistics/mch'
                            ],
                            [
                                'name' => '秒杀统计',
                                'route' => 'mall/miaosha-statistics/index'
                            ],
                            [
                                'name' => 'N元任选统计',
                                'route' => 'mall/pick-statistics/index'
                            ],
                            [
                                'name' => '拼团统计',
                                'route' => 'mall/pintuan-statistics/index'
                            ],
                            [
                                'name' => '步数宝统计',
                                'route' => 'mall/step-statistics/index'
                            ],
                            [
                                'name' => '步数兑换统计',
                                'route' => 'mall/step-statistics/ex'
                            ],
                            [
                                'name' => '商品批发统计',
                                'route' => 'mall/wholesale-statistics/index'
                            ],
                            [
                                'name' => '裂变拆红包统计',
                                'route' => 'mall/fxhb-statistics/index'
                            ],
                            [
                                'name' => '区域代理统计',
                                'route' => 'mall/region-statistics/index'
                            ],
                            [
                                'name' => '股东分红统计',
                                'route' => 'mall/stock-statistics/index'
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '门店统计'),
                        'route' => 'mall/order-statistics/shop',
                    ],
                ],
            ],

            [
                'name' => \Yii::t('common', '多平台'),
                'key' => 'app-manage',
                'route' => '',
                'icon' => 'statics/img/mall/nav/applets.png',
                'icon_active' => 'statics/img/mall/nav/applets-active.png',
                'children' => [],
            ],
            [
                'name' => \Yii::t('common', 'PC管理'),
                'key' => 'pc_manage',
                'route' => '',
                'icon' => 'statics/img/mall/nav/pc.png',
                'icon_active' => 'statics/img/mall/nav/pc-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '基础设置'),
                        'route' => 'pc/index/setting',
                    ],
                    [
                        'name' => \Yii::t('common', '首页轮播图'),
                        'route' => 'pc/banner/index',
                        'action' => [
                            [
                                'name' => '轮播图编辑',
                                'route' => 'pc/banner/edit',
                            ],
                            [
                                'name' => '轮播图删除',
                                'route' => 'pc/banner/destroy',
                            ],
                        ]
                    ],
                    [
                        'name' => \Yii::t('common', '首页广告图'),
                        'route' => 'pc/index/ad-config',
                    ],
                    [
                        'name' => \Yii::t('common', '导航管理'),
                        'route' => 'pc/nav/index',
                    ],
                ]
            ], // @czs
            [
                'name' => \Yii::t('common', '店铺管理'),
                'route' => '',
                'icon' => 'statics/img/mall/nav/mall-manage.png',
                'icon_active' => 'statics/img/mall/nav/mall-manage-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '店铺设计'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '轮播图'),
                                'route' => 'mall/mall-banner',
                                'action' => [
                                    [
                                        'name' => '轮播图(S|U)',
                                        'route' => 'mch/store/slide-edit',
                                    ],
                                    [
                                        'name' => '轮播图删除',
                                        'route' => 'mch/store/slide-del',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '导航管理'),
                                'route' => 'mall/home-nav/index',
                                'action' => [
                                    [
                                        'name' => '导航图标(S|U)',
                                        'route' => 'mall/home-nav/edit',
                                    ],
                                    [
                                        'name' => '导航图标删除',
                                        'route' => 'mall/home-nav/destroy',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '商城风格'),
                                'route' => 'mall/theme-color/index',
                            ],
                            [
                                'name' => \Yii::t('common', '图片魔方'),
                                'route' => 'mall/home-block/index',
                                'action' => [
                                    [
                                        'name' => '图片魔方删除',
                                        'route' => 'mall/home-block/destroy',
                                    ],
                                    [
                                        'name' => '图片魔方(S|U)',
                                        'route' => 'mall/home-block/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '标签栏'),
                                'route' => 'mall/navbar/setting',
                                'action' => [
                                    [
                                        'name' => '恢复默认设置',
                                        'is_mune' => false,
                                        'route' => 'mall/navbar/default',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '首页布局'),
                                'route' => 'mall/home-page/setting',
                            ],
                            [
                                'name' => \Yii::t('common', '用户中心'),
                                'route' => 'mall/user-center/setting',
                            ],
                            [
                                'name' => \Yii::t('common', '下单表单'),
                                'route' => 'mall/order-form/list',
                                'action' => [
                                    [
                                        'name' => '下单表单编辑',
                                        'route' => 'mall/order-form/setting',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '自定义海报'),
                                'route' => 'mall/poster/setting',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '页面管理'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '页面管理'),
                                'route' => 'mall/app-page/index',
                            ],
                            [
                                'name' => \Yii::t('common', '页面标题'),
                                'route' => 'mall/page-title/setting',
                            ],
                            [
                                'key' => 'copyright',
                                'name' => \Yii::t('common', '版权设置'),
                                'route' => 'mall/copyright/setting',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '内容管理'),
                        'route' => 'mall/article/index',
                        'icon' => 'statics/img/mall/nav/content.png',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '文章'),
                                'route' => 'mall/article/index',
                                'action' => [
                                    [
                                        'name' => '文章(S|U)',
                                        'route' => 'mall/article/edit',
                                    ],
                                    [
                                        'name' => '文章删除',
                                        'route' => 'mall/article/delete',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'topic',
                                'name' => \Yii::t('common', '专题分类'),
                                'route' => 'mall/topic-type/index',
                                'action' => [
                                    [
                                        'name' => '专题分类删除',
                                        'route' => 'mall/topic-type/delete',
                                    ],
                                    [
                                        'name' => '专题分类(S|U)',
                                        'route' => 'mall/topic-type/edit',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'topic',
                                'name' => \Yii::t('common', '专题'),
                                'route' => 'mall/topic/index',
                                'action' => [
                                    [
                                        'name' => '专题删除',
                                        'route' => 'mall/topic/delete',
                                    ],
                                    [
                                        'name' => '专题(S|U)',
                                        'route' => 'mall/topic/edit',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'video',
                                'name' => \Yii::t('common', '视频'),
                                'route' => 'mall/video/index',
                                'action' => [
                                    [
                                        'name' => '视频(S|U)',
                                        'route' => 'mall/video/edit',
                                    ],
                                    [
                                        'name' => '视频删除',
                                        'route' => 'mall/video/delete',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '门店管理'),
                                'route' => 'mall/store/index',
                                'action' => [
                                    [
                                        'name' => '门店删除',
                                        'route' => 'mall/store/destroy',
                                    ],
                                    [
                                        'name' => '设置默认门店',
                                        'route' => 'mall/store/default',
                                    ],
                                    [
                                        'name' => '门店(S|U)',
                                        'route' => 'mall/store/edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'rule_user', // 员工账号不能显示
                        'name' => \Yii::t('common', '员工管理'),
                        'icon' => 'statics/img/mall/nav/staff.png',
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '基础设置'),
                                'icon' => 'icon-manage',
                                'route' => 'mall/role-setting/index',
                            ],
                            [
                                'name' => \Yii::t('common', '角色列表'),
                                'icon' => 'icon-manage',
                                'route' => 'mall/role/index',
                                'action' => [
                                    [

                                        'name' => '添加角色',
                                        'route' => 'mall/role/create',
                                    ],
                                    [
                                        'name' => '编辑角色',
                                        'route' => 'mall/role/edit',
                                    ],
                                    [
                                        'name' => '角色(U)',
                                        'route' => 'mall/role/update',
                                    ],
                                    [
                                        'name' => '角色删除',
                                        'route' => 'mall/role/destroy',
                                    ],
                                    [
                                        'name' => '角色(S)',
                                        'route' => 'mall/role/store',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '员工列表'),
                                'route' => 'mall/role-user/index',
                                'action' => [
                                    [
                                        'name' => '员工(U)',
                                        'route' => 'mall/role-user/update',
                                    ],
                                    [
                                        'name' => '员工删除',
                                        'route' => 'mall/role-user/destroy',
                                    ],
                                    [
                                        'name' => '员工(S)',
                                        'route' => 'mall/role-user/store',
                                    ],
                                    [
                                        'name' => '添加员工',
                                        'route' => 'mall/role-user/create',
                                    ],
                                    [
                                        'name' => '编辑员工',
                                        'route' => 'mall/role-user/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '操作记录'),
                                'route' => 'mall/role-user/action',
                                'action' => [
                                    [
                                        'name' => '操作详情',
                                        'route' => 'mall/role-user/action-edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '下载中心'),
                        'route' => 'mall/file/index',
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '商品管理'),
                'route' => 'mall/goods/index',
                'icon' => 'statics/img/mall/nav/goods.png',
                'icon_active' => 'statics/img/mall/nav/goods-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '商品列表'),
                        'route' => 'mall/goods/index',
                        'action' => [
                            [
                                'name' => '商品删除',
                                'route' => 'mall/goods/destroy',
                            ],
                            [
                                'name' => '商品批量操作删除',
                                'route' => 'mall/goods/batch-destroy',
                            ],
                            [
                                'name' => '商品(上架|下架)',
                                'route' => 'mall/goods/goods-up-down',
                            ],
                            [
                                'name' => '商品(S|U)',
                                'route' => 'mall/goods/edit',
                            ],
                            [
                                'name' => '商品导出',
                                'route' => 'mall/goods/export-goods-list',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '批量导入'),
                        'route' => 'mall/goods/import-data',
                        'action' => [
                            [
                                'name' => '商品导入历史',
                                'route' => 'mall/goods/import-goods-log',
                            ],
                            [
                                'name' => '分类导入历史',
                                'route' => 'mall/cat/import-cat-log',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '商品分类'),
                        'route' => 'mall/cat/index',
                        'action' => [
                            [
                                'name' => '商品分类删除',
                                'route' => 'mall/cat/cat-destroy',
                            ],
                            [
                                'name' => '商品分类(S|U)',
                                'route' => 'mall/cat/edit',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '规格模板'),
                        'route' => 'mall/goods-attr-template/index',
                    ],
                    [
                        'name' => \Yii::t('common', '参数模板'),
                        'route' => 'mall/goods-params-template/index',
                    ],
                    [
                        'name' => \Yii::t('common', '素材管理'),
                        'route' => 'mall/material/index',
                    ],
                    [
                        'name' => \Yii::t('common', '快速购买'),
                        'route' => 'mall/quick-shop/index',
                    ],
                    [
                        'name' => \Yii::t('common', '推荐设置'),
                        'route' => 'mall/goods/recommend-setting',
                    ],
                    [
                        'name' => \Yii::t('common', '商品热搜'),
                        'route' => 'mall/goods-hot-search/get-all',
                        'action' => [
                            [
                                'name' => '商品服务删除',
                                'route' => 'mall/goods-hot-search/edit',
                            ],
                            [
                                'name' => '商品服务删除',
                                'route' => 'mall/goods-hot-search/destroy',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '商品服务'),
                        'route' => 'mall/service/index',
                        'action' => [
                            [
                                'name' => '商品服务删除',
                                'route' => 'mall/service/destroy',
                            ],
                            [
                                'name' => '商品服务(S|U)',
                                'route' => 'mall/service/edit',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '淘宝CSV'),
                        'route' => 'mall/goods/taobao-csv',
                    ],
                    [
                        'key' => 'assistant',
                        'name' => \Yii::t('common', '采集商品'),
                        'route' => 'mall/assistant/collect',
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '订单管理'),
                'route' => 'mall/order/index',
                'icon' => 'statics/img/mall/nav/order.png',
                'icon_active' => 'statics/img/mall/nav/order-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '订单列表'),
                        'route' => 'mall/order/index',
                        'action' => [
                            [
                                'name' => '订单移入回收站',
                                'route' => 'mall/order/edit',
                            ],
                            [
                                'name' => '订单添加备注',
                                'route' => 'mall/order/seller-comments',
                            ],
                            [
                                'name' => '订单发货',
                                'route' => 'mall/order/send',
                            ],
                            [
                                'name' => '订单打印',
                                'route' => 'mall/order/print',
                            ],
                            [
                                'name' => '订单申请状态',
                                'route' => 'mall/order/apply-delete-status',
                            ],
                            [
                                'name' => '订单货到付款状态',
                                'route' => 'mall/order/confirm',
                            ],
                            [
                                'name' => '订单详情',
                                'route' => 'mall/order/detail',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '售后订单'),
                        'route' => 'mall/order/refund',
                        'action' => [
                            [
                                'name' => '售后详情',
                                'route' => 'mall/order/refund-detail',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '评价管理'),
                        'route' => 'mall/order-comments/index',
                        'action' => [
                            [
                                'name' => '订单评价删除',
                                'route' => 'mch/comment/delete-status',
                            ],
                            [
                                'name' => '订单评价隐藏',
                                'route' => 'mch/comment/hide-status',
                            ],
                            [
                                'name' => '订单评价回复',
                                'route' => 'mall/order-comments/reply',
                            ],
                            [
                                'name' => '订单评价(S|U)',
                                'route' => 'mall/order-comments/edit',
                            ],
                            [
                                'name' => '订单评价(S|U)',
                                'route' => 'mall/order-comment-templates/index',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '批量发货'),
                        'route' => 'mall/order/batch-send',
                        'action' => [
                            [
                                'name' => '模版下载',
                                'route' => 'mall/order/batch-send-model',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '分销订单'),
                        'route' => 'mall/mch/share-order',
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '用户管理'),
                'route' => 'mall/user/index',
                'icon' => 'statics/img/mall/nav/user.png',
                'icon_active' => 'statics/img/mall/nav/user-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '用户管理'),
                        'route' => 'mall/user/index',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '用户列表'),
                                'route' => 'mall/user/index',
                                'action' => [
                                    [
                                        'name' => '用户列表',
                                        'route' => 'mall/user/get-user',
                                    ],
                                    [
                                        'name' => '用户删除',
                                        'route' => 'mall/user/del',
                                    ],
                                    [
                                        'name' => '用户积分充值',
                                        'route' => 'mall/user/rechange',
                                    ],
                                    [
                                        'name' => '用户金额充值',
                                        'route' => 'mall/user/recharge-money',
                                    ],
                                    [
                                        'name' => '用户卡券删除',
                                        'route' => 'mall/user/coupon-del',
                                    ],
                                    [
                                        'name' => '用户卡券',
                                        'route' => 'mall/user/coupon',
                                    ],
                                    [
                                        'name' => '用户编辑',
                                        'route' => 'mall/user/edit',
                                    ],
                                    [
                                        'name' => '用户余额',
                                        'route' => 'mall/user/recharge-money-log',
                                    ],
                                    [
                                        'name' => '积分充值记录',
                                        'route' => 'mall/user/rechange-log',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '会员等级'),
                                'route' => 'mall/mall-member/index',
                                'action' => [
                                    [
                                        'name' => '会员等级状态(启用|禁用)',
                                        'route' => 'mall/mall-member/status',
                                    ],
                                    [
                                        'name' => '会员等级删除',
                                        'route' => 'mall/mall-member/destroy',
                                    ],
                                    [
                                        'name' => '用户会员等级(S|U)',
                                        'route' => 'mall/mall-member/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '会员购买'),
                                'route' => 'mall/user/level-log',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '核销管理'),
                        'route' => 'mall/clerk/order',
                        'icon' => 'statics/img/mall/nav/user.png',
                        'icon_active' => 'statics/img/mall/nav/user-active.png',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '核销员'),
                                'route' => 'mall/user/clerk',
                                'action' => [
                                    [
                                        'name' => '用户(设置/取消核销员)',
                                        'route' => 'mall/user/clerk-edit',
                                    ],
                                    [
                                        'name' => '用户核销员列表',
                                        'route' => 'mall/user/clerk',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '核销订单'),
                                'route' => 'mall/clerk/order',
                            ],
                            [
                                'name' => \Yii::t('common', '核销卡券'),
                                'route' => 'mall/clerk/card',
                            ],
                        ],
                    ],
                    [
                        'key' => 'share',
                        'name' => \Yii::t('common', '分销商管理'),
                        'route' => 'mall/share/index',
                        'icon' => 'statics/img/mall/nav/share.png',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '基础设置'),
                                'route' => 'mall/share/basic',
                                'action' => [
                                    [
                                        'name' => '分享二维码',
                                        'route' => 'mall/share/qrcode',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '自定义设置'),
                                'route' => 'mall/share/customize',
                            ],
                            [
                                'name' => \Yii::t('common', '分销商'),
                                'route' => 'mall/share/index',
                                'action' => [
                                    [
                                        'name' => '分销商添加备注',
                                        'route' => 'mall/share/seller-comments',
                                    ],
                                    [
                                        'name' => '分销商佣金设置',
                                        'route' => 'mall/share/setting',
                                    ],
                                    [
                                        'name' => '分销商批量设置',
                                        'route' => 'mall/share/batch',
                                    ],
                                    [
                                        'name' => '分销商申请审核',
                                        'route' => 'mall/share/status',
                                    ],
                                    [
                                        'name' => '分销商确认打款',
                                        'route' => 'mall/share/confirm',
                                    ],
                                    [
                                        'name' => '设置推广海报',
                                        'route' => 'mall/share/qrcode',
                                    ],
                                    [
                                        'name' => '分销商删除',
                                        'route' => 'mall/share/del',
                                    ],
                                    [
                                        'name' => '分销商自定义设置',
                                        'route' => 'mall/share/custom',
                                    ],
                                    [
                                        'name' => '提现详情',
                                        'route' => 'mall/share/cash',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '分销商等级'),
                                'route' => 'mall/share/level',
                                'action' => [
                                    [
                                        'name' => '分销商等级编辑',
                                        'route' => 'mall/share/level-edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '分销订单'),
                                'route' => 'mall/share/order',
                            ],
                            [
                                'name' => \Yii::t('common', '分销排行'),
                                'route' => 'mall/share-statistics/index',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '财务管理'),
                'route' => '',
                'icon' => 'statics/img/mall/nav/finance.png',
                'icon_active' => 'statics/img/mall/nav/finance-active.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '对账单'),
                        'route' => 'mall/price-statistics/index',
                    ],
                    [
                        'name' => \Yii::t('common', '提现管理'),
                        'route' => 'mall/finance/cash',
                    ],
                    [
                        'name' => \Yii::t('common', '收支记录'),
                        'route' => 'mall/mch/account-log',
                    ],
                    [
                        'name' => \Yii::t('common', '提现记录'),
                        'route' => 'mall/mch/cash-log',
                    ],
                    [
                        'name' => \Yii::t('common', '结算记录'),
                        'route' => 'mall/mch/order-close-log',
                    ],
                ],
            ],
            [
                'key' => 'course',
                'name' => \Yii::t('common', '教程管理'),
                'icon' => 'statics/img/mall/nav/study.png',
                'route' => 'mall/tutorial/index',
                'children' => [
                    [
                        'name' => \Yii::t('common', '操作教程'),
                        'route' => 'mall/tutorial/index',
                    ],
                    [
                        'key' => 'course_setting', // 超级管理员显示
                        'name' => \Yii::t('common', '教程设置'),
                        'route' => 'mall/tutorial/setting',
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '系统工具'),
                'icon' => 'statics/img/mall/nav/tool.png',
                'icon_active' => 'statics/img/mall/nav/tool-active.png',
                'route' => '',
                'children' => [
                    [
                        'key' => 'base_setting',
                        'ignore' => ['ind'],
                        'name' => \Yii::t('common', '基础设置'),
                        'route' => 'mall/we7/base-setting',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'attachment',
                        'ignore' => ['ind'],
                        'name' => \Yii::t('common', '账户上传管理'),
                        'route' => 'admin/setting/attachment',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'permission_manage', // 超级管理员
                        'ignore' => ['ind'],
                        'name' => \Yii::t('common', '权限分配'),
                        'route' => 'mall/we7/auth',
                    ],
                    [
                        'key' => 'small_procedure',
                        'name' => \Yii::t('common', '小程序管理'),
                        'ignore' => ['ind'],
                        'route' => 'admin/mall/index',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'queue_service',
                        'name' => \Yii::t('common', '队列服务'),
                        'ignore' => ['ind'],
                        'route' => 'admin/setting/queue-service',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'upload_admin', // 独立版 weiqing线下版 超级管理员
                        'name' => \Yii::t('common', '更新'),
                        'ignore' => ['ind', 'we7'],
                        'route' => 'admin/update/index',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'overrun',
                        'name' => \Yii::t('common', '超限设置'),
                        'route' => 'admin/setting/overrun',
                        'ignore' => ['ind'],
                        'icon' => 'icon-qinglihuancun',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                    ],
                    [
                        'key' => 'mall_notice',
                        'name' => \Yii::t('common', '发布公告'),
                        'route' => 'admin/notice/notice',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                        'ignore' => ['ind'],
                    ],
                    [
                        'key' => 'wxapp',
                        'name' => \Yii::t('common', '微信开放平台'),
                        'route' => 'admin/setting/wxapp',
                        'params' => [
                            '_layout' => 'mall',
                        ],
                        'ignore' => ['ind'],
                    ],
//                    [
//                        'name' => \Yii::t('common', 'v3商城导入'),
//                        'route' => 'mall/import/index',
//                        'params' => [
//                            '_layout' => 'mall',
//                        ],
//                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '营销中心'),
                'route' => 'mall/plugin/index',
                'icon' => 'statics/img/mall/nav/plugins.png',
                'icon_active' => 'statics/img/mall/nav/plugins.png',
                'children' => [
                    [
                        'name' => \Yii::t('common', '全部应用'),
                        'key' => 'plugins',
                        'route' => 'mall/plugin/index',
                        'action' => [
                            [
                                'name' => 'NotInstallList',
                                'route' => 'mall/plugin/not-install-list',
                            ],
                            [
                                'name' => 'Detail',
                                'route' => 'mall/plugin/detail',
                            ],
                            [
                                'name' => 'Buy',
                                'route' => 'mall/plugin/buy',
                            ],
                            [
                                'name' => 'Pay',
                                'route' => 'mall/plugin/pay',
                            ],
                            [
                                'name' => 'Download',
                                'route' => 'mall/plugin/download',
                            ],
                            [
                                'name' => 'Install',
                                'route' => 'mall/plugin/install',
                            ],
                            [
                                'name' => 'Uninstall',
                                'route' => 'mall/plugin/uninstall',
                            ],
                            [
                                'name' => 'CatManager',
                                'route' => 'mall/plugin/cat-manager',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '余额'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '基础设置'),
                                'route' => 'mall/recharge/config',
                            ],
                            [
                                'name' => \Yii::t('common', '充值管理'),
                                'route' => 'mall/recharge/index',
                                'action' => [
                                    [
                                        'name' => '充值编辑',
                                        'route' => 'mall/recharge/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '余额收支'),
                                'route' => 'mall/user/balance-log',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '积分'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '积分设置'),
                                'route' => 'mall/user/integral-setting',
                            ],
                            [
                                'name' => \Yii::t('common', '积分记录'),
                                'route' => 'mall/user/integral-log',
                            ],
                            [
                                'name' => \Yii::t('common', '积分收支'),
                                'route' => 'mall/integral-statistics/index',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '卡券'),
                        'route' => 'mall/card/index',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '卡券列表'),
                                'route' => 'mall/card/index',
                                'action' => [
                                    [
                                        'name' => '卡券编辑',
                                        'route' => 'mall/card/edit',
                                    ],
                                    [
                                        'name' => '卡券发放',
                                        'route' => 'mall/card/send',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '用户卡券'),
                                'route' => 'mall/user/card',
                                'action' => [
                                    [
                                        'name' => '用户卡券删除',
                                        'route' => 'mall/card/card-destroy',
                                    ],
                                    [
                                        'name' => '用户卡券批量删除',
                                        'route' => 'mall/card/card-batch-destroy',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '卡券统计'),
                                'route' => 'mall/send-statistics/card',
                                'params' => [
                                    'type' => 'card',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'coupon',
                        'name' => \Yii::t('common', '优惠券'),
                        'route' => 'mall/coupon/index',
                        'action' => [
                            [
                                'name' => '优惠券改善',
                                'route' => 'mall/coupon/send',
                            ],
                            [
                                'name' => '优惠券编辑',
                                'route' => 'mall/coupon/edit',
                            ],
                        ],
                        'children' => [
                            [
                                'key' => 'coupon',
                                'name' => \Yii::t('common', '优惠券管理'),
                                'route' => 'mall/coupon/index',
                                'action' => [
                                    [
                                        'name' => '优惠券分类删除',
                                        'route' => 'mall/coupon/delete-cat',
                                    ],
                                    [
                                        'name' => '优惠券删除',
                                        'route' => 'mall/coupon/delete',
                                    ],
                                    [
                                        'name' => '优惠券发放',
                                        'route' => 'mall/coupon/send',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'coupon',
                                'name' => \Yii::t('common', '自动发放'),
                                'route' => 'mall/coupon-auto-send/index',
                                'action' => [
                                    [
                                        'name' => '优惠券自动发放',
                                        'route' => 'mall/coupon/auto-send-edit',
                                    ],
                                    [
                                        'name' => '优惠券自动发放方案删除',
                                        'route' => 'mall/coupon/auto-send-delete',
                                    ],
                                    [
                                        'name' => '自动发放编辑',
                                        'route' => 'mall/coupon/auto-send-edit',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'coupon',
                                'name' => \Yii::t('common', '使用记录'),
                                'route' => 'mall/coupon/use-log',
                            ],
                            [
                                'name' => \Yii::t('common', '发放统计'),
                                'route' => 'mall/send-statistics/index',
                                'params' => [
                                    'type' => 'coupon',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'live',
                        'name' => \Yii::t('common', '直播管理'),
                        'route' => 'mall/live/index',
                        'children' => [
                            [
                                'key' => 'live',
                                'name' => \Yii::t('common', '直播间管理'),
                                'route' => 'mall/live/index',
                                'action' => [
                                    [
                                        'name' => '直播编辑',
                                        'route' => 'mall/live/live-edit',
                                    ],
                                    [
                                        'name' => '添加商品',
                                        'route' => 'mall/live/add-goods',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'live',
                                'name' => \Yii::t('common', '直播商品'),
                                'route' => 'mall/live/goods',
                                'action' => [
                                    [
                                        'name' => '直播商品编辑',
                                        'route' => 'mall/live/goods-edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'full-reduce',
                        'name' => \Yii::t('common', '满减设置'),
                        'route' => 'mall/full-reduce/index',
                        'action' => [
                            [
                                'name' => '满减编辑',
                                'route' => 'mall/full-reduce/edit',
                            ],
                        ]
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '设置'),
                'route' => '',
                'icon' => 'statics/img/mall/nav/setting.png',
                'icon_active' => 'statics/img/mall/nav/setting-active.png',
                'children' => [
                    [
                        'key' => 'mch_setting',
                        'name' => \Yii::t('common', '店铺设置'),
                        'route' => 'mall/mch/setting',
                    ],
                    [
                        'key' => 'mch_manage',
                        'name' => \Yii::t('common', '门店管理'),
                        'route' => 'mall/mch/manage',
                    ],
                    [
                        'name' => \Yii::t('common', '基础设置'),
                        'route' => 'mall/index/setting',
                    ],
                    [
                        'name' => \Yii::t('common', '商品设置'),
                        'route' => 'mall/index/goods',
                    ],
                    [
                        'name' => \Yii::t('common', '支付管理'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '支付方式'),
                                'route' => 'mall/pay-type/index',
                                'action' => [
                                    [
                                        'name' => '支付方式编辑',
                                        'route' => 'mall/pay-type/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '支付设置'),
                                'route' => 'mall/index/pay',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '消息提醒'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '短信提醒'),
                                'route' => 'mall/sms/setting',
                            ],
                            [
                                'name' => \Yii::t('common', '邮件通知'),
                                'route' => 'mall/index/mail',
                            ],
                            [
                                'name' => \Yii::t('common', '小程序提醒'),
                                'route' => 'mall/index/template',
                            ],
                            [
                                'key' => 'mptemplate',
                                'name' => \Yii::t('common', '公众号提醒'),
                                'route' => 'mall/template-msg/setting',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '公众号配置',
                                'route' => 'mall/template-msg/setting',
                            ],
                            [
                                'name' => '短信通知',
                                'route' => 'mall/sms/setting',
                            ],
                            [
                                'name' => '邮件通知',
                                'route' => 'mall/index/mail',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '物流设置'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '规则设置'),
                                'route' => 'mall/index/rule',
                                'action' => [
                                    [
                                        'name' => '运费规则',
                                        'route' => 'mall/postage-rule/index',
                                    ],
                                    [
                                        'name' => '运费规则状态(U)',
                                        'route' => 'mall/postage-rule/edit/status',
                                    ],
                                    [
                                        'name' => '运费规则删除',
                                        'route' => 'mall/postage-rule/destroy',
                                    ],
                                    [
                                        'name' => '运费规则(S|U)',
                                        'route' => 'mall/postage-rule/edit',
                                    ],
                                    [
                                        'name' => '包邮规则',
                                        'route' => 'mall/free-delivery-rules/index',
                                    ],
                                    [
                                        'name' => '包邮规则删除',
                                        'route' => 'mall/free-delivery-rules/delete',
                                    ],
                                    [
                                        'name' => '包邮规则(S|U)',
                                        'route' => 'mall/free-delivery-rules/edit',
                                    ],
                                    [
                                        'name' => '起送规则',
                                        'route' => 'mall/offer-price/index',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '退货地址'),
                                'route' => 'mall/refund-address/index',
                                'action' => [
                                    [
                                        'name' => '退货地址编辑',
                                        'route' => 'mall/refund-address/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '区域购买'),
                                'route' => 'mall/territorial-limitation/index',
                            ],
                            [
                                'name' => \Yii::t('common', '电子面单'),
                                'route' => 'mall/express/index',
                                'action' => [
                                    [
                                        'name' => '电子面单删除',
                                        'route' => 'mall/express/delete',
                                    ],
                                    [
                                        'name' => '电子面单打印(S|U)',
                                        'route' => 'mall/express/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '小票打印'),
                                'route' => 'mall/printer/index',
                                'action' => [
                                    [
                                        'name' => '小票打印设置',
                                        'route' => 'mall/printer/setting',
                                    ],
                                    [
                                        'name' => '小票打印编辑',
                                        'route' => 'mall/printer/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => \Yii::t('common', '发货单管理'),
                                'route' => 'mall/order-send-template/index',
                                'action' => [
                                    [
                                        'name' => '发货单编辑',
                                        'route' => 'mall/order-send-template/edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '同城配送'),
                        'route' => '',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '基础设置'),
                                'route' => 'mall/delivery/index',
                            ],
                            [
                                'key' => 'wlhulian',
                                'name' => \Yii::t('common', '聚合配送'),
                                'route' => 'mall/wlhulian/index',
                            ],
                            [
                                'name' => \Yii::t('common', '配送设置'),
                                'route' => 'mall/city-service/index',
                                'icon' => 'el-icon-setting',
                                'action' => [
                                    [
                                        'name' => '商家编辑',
                                        'route' => 'mall/city-service/edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'attachment',
                        'name' => \Yii::t('common', '上传设置'),
                        'icon' => 'icon-shangchuan',
                        'route' => 'mall/attachment/attachment',
                    ],
                    [
                        'name' => \Yii::t('common', '公众号管理'),
                        'key' => 'wechat_manage',
                        'route' => 'mall/wechat/setting',
                        'children' => [
                            [
                                'name' => \Yii::t('common', '基础设置'),
                                'route' => 'mall/wechat/setting',
                            ],
                            [
                                'key' => 'mpvideo',
                                'name' => \Yii::t('common', '视频号设置'),
                                'route' => 'mall/wechat/video',
                            ],
                            [
                                'key' => 'mpvideo',
                                'name' => \Yii::t('common', '服务器配置'),
                                'route' => 'mall/wechat/server',
                            ],
                            [
                                'key' => 'mpvideo',
                                'name' => \Yii::t('common', '自动回复'),
                                'route' => 'mall/wechat/reply',
                                'action' => [
                                    [
                                        'name' => '关键词回复编辑',
                                        'route' => 'mall/wechat/keyword-rule'
                                    ]
                                ]
                            ],
                            [
                                'key' => 'mpvideo',
                                'name' => \Yii::t('common', '菜单设置'),
                                'route' => 'mall/wechat/menus',
                            ],
                        ]
                    ],
                    [
                        'key' => 'assistant',
                        'name' => \Yii::t('common', '采集助手'),
                        'route' => 'mall/assistant/index',
                    ],
                ],
            ],
        ];
        $plugins = \Yii::$app->plugin->list;
        $pluginMenus = [];
        $platformMenus = [];
        foreach ($plugins as $plugin) {
            $pluginClass = 'app\\plugins\\' . $plugin->name . '\\Plugin';
            /** @var Plugin $object */
            if (!class_exists($pluginClass)) {
                continue;
            }
            $object = new $pluginClass();
            if (method_exists($object, 'getMenus') && $isPluginMenus) {
                $menus = $object->getMenus();
                $newMenus = [
                    'name' => $object->getDisplayName(),
                    'icon' => '',
                    'children' => $menus,
                    'route' => $menus[0]['route'] ?? '',
                ];
                $pluginMenus[] = $newMenus;
            }
            // 属于平台插件菜单
            if (method_exists($object, 'getIsPlatformPlugin')) {
                if ($object->getIsPlatformPlugin()) {
                    $platformMenus[] = [
                        'name' => str_replace('小程序', '', $object->getDisplayName()),
                        'route' => $object->getIndexRoute(),
                    ];
                }
            }
        }

        foreach ($mallMenus as &$menu) {
            $menu = self::setExtraMenu($menu, $pluginMenus, $platformMenus, $isPluginMenus);
        }
        unset($menu);
        return $mallMenus;
    }

    public static function setExtraMenu($item, $pluginMenus, $platformMenus, $isPluginMenus)
    {
        if (isset($item['key']) && $item['key'] == 'plugins' && $isPluginMenus) {
            $item['children'] = $pluginMenus;
        }

        if (isset($item['key']) && $item['key'] == 'app-manage') {
            $item['children'] = array_merge($item['children'], $platformMenus);
        }

        if (isset($item['children'])) {
            foreach ($item['children'] as $key => $child) {
                $item['children'][$key] = self::setExtraMenu($child, $pluginMenus, $platformMenus, $isPluginMenus);
            }
        }

        return $item;
    }

    /**
     * 独立版
     * @return array
     */
    public static function getAdminMenus()
    {
        return [
            [
                'name' => \Yii::t('common', '账户管理'),
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => \Yii::t('common', '我的账户'),
                        'route' => 'admin/user/me',
                        'icon' => 'icon-person',
                    ],
                    [
                        'key' => 'account_list', // 超级管理员 显示
                        'name' => \Yii::t('common', '账户列表'),
                        'route' => 'admin/user/index',
                        'icon' => 'icon-liebiao',
                    ],
                    [
                        'key' => 'add_account', // 超级管理员 显示
                        'name' => \Yii::t('common', '新增子账户'),
                        'route' => 'admin/user/edit',
                        'icon' => 'icon-add1',
                    ],
                    [
                        'key' => 'register_audit', // 超级管理员 显示
                        'name' => \Yii::t('common', '注册审核'),
                        'route' => 'admin/user/register',
                        'icon' => 'icon-liebiao',
                    ],
                    [
                        'key' => 'message_remind',
                        'name' => \Yii::t('common', '子账户到期提醒设置'),
                        'route' => 'admin/setting/message-remind',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '小程序商城'),
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'key' => 'small_procedure',
                        'name' => \Yii::t('common', '小程序商城管理'),
                        'route' => 'admin/mall/index',
                        'icon' => 'icon-shanghu',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                        'action' => [
                            [
                                'name' => '添加编辑小程序',
                                'route' => 'admin/app/edit',
                            ],
                            [
                                'name' => '进入小程序后台',
                                'route' => 'admin/app/entry',
                            ],
                            [
                                'name' => '删除小程序商城',
                                'route' => 'admin/app/delete',
                            ],
                            [
                                'name' => '小程序回收站',
                                'route' => 'admin/app/recycle',
                            ],
                            [
                                'name' => '设置回收站',
                                'route' => 'admin/app/set-recycle',
                            ],
                            [
                                'name' => '小程序禁用',
                                'route' => 'admin/app/disabled',
                            ],
                        ],
                    ],
                    [
                        'name' => \Yii::t('common', '回收站'),
                        'route' => 'admin/app/recycle',
                        'icon' => 'icon-huishouzhan',
                    ],
                ],
            ],
            [
                'name' => \Yii::t('common', '应用管理'),
                'route' => 'admin/app-manage/index',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => \Yii::t('common', '全部应用'),
                        'route' => 'admin/app-manage/index',
                        'icon' => 'icon-settings',
                        'action' => [
                            [
                                'name' => '应用详情',
                                'route' => 'admin/app-manage/detail',
                                'icon' => 'icon-add1',
                            ],
                            [
                                'name' => '应用编辑',
                                'route' => 'admin/app-manage/edit',
                                'icon' => 'icon-add1',
                            ],
                        ]
                    ],
                    [
                        'name' => \Yii::t('common', '应用分组'),
                        'key' => 'app_groups',
                        'route' => 'admin/app-manage/group',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'name' => \Yii::t('common', '订单管理'),
                        'route' => 'admin/app-manage/order',
                        'icon' => 'icon-settings',
                        'action' => [
                            [
                                'name' => \Yii::t('common', '下载中心'),
                                'route' => 'admin/app-manage/file',
                                'icon' => 'icon-add1',
                            ]
                        ]
                    ],
                ]
            ],
            [
                'name' => \Yii::t('common', '发布公告'),
                'key' => 'super-notice',
                'route' => 'admin/notice/notice',
                'icon' => 'icon-setup',
                'params' => [
                    '_layout' => 'admin',
                ],
            ],
            [
                'name' => \Yii::t('common', '设置'),
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'key' => 'system_setting', // 超级管理员 显示
                        'name' => \Yii::t('common', '系统设置'),
                        'route' => 'admin/setting/index',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'key' => 'pay_setting', // 超级管理员 显示
                        'name' => \Yii::t('common', '支付设置'),
                        'route' => 'admin/setting/pay-setting',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'key' => 'attachment',
                        'name' => \Yii::t('common', '账户上传管理'),
                        'icon' => 'icon-shangchuan',
                        'route' => 'admin/setting/attachment',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                    ],
                    [
                        'key' => 'overrun',
                        'name' => \Yii::t('common', '超限设置'),
                        'route' => 'admin/setting/overrun',
                        'icon' => 'icon-qinglihuancun',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                    ],
                    [
                        'key' => 'wxapp',
                        'name' => \Yii::t('common', '微信开放平台'),
                        'route' => 'admin/setting/wxapp',
                        'icon' => 'icon-qinglihuancun',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                    ],
                    [
                        'key' => 'communication', // 超级管理员 显示
                        'name' => \Yii::t('common', '通联支付配置'),
                        'route' => 'admin/setting/communication',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'key' => 'global_copyright',
                        'name' => \Yii::t('common', '版权设置'),
                        'route' => 'admin/setting/copyright',
                        'icon' => 'icon-qinglihuancun',
                        'params' => [
                            '_layout' => 'admin',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'upload_admin', // 超级管理员 显示
                'name' => \Yii::t('common', '更新'),
                'route' => 'admin/update/index',
                'icon' => 'icon-upgrade',
                'params' => [
                    '_layout' => 'admin',
                ],
            ],
            [
                'key' => 'queue_service',
                'name' => \Yii::t('common', '队列服务'),
                'route' => 'admin/setting/queue-service',
                'icon' => 'icon-qinglihuancun',
                'params' => [
                    '_layout' => 'admin',
                ],
            ],
            [
                'name' => \Yii::t('common', '聚合配送'),
                'route' => 'admin/logistics/index',
                'key' => 'wlhulian',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => \Yii::t('common', '基础配置'),
                        'route' => 'admin/logistics/index',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'name' => \Yii::t('common', '设置门店'),
                        'route' => 'admin/logistics/store',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'name' => \Yii::t('common', '商城列表'),
                        'route' => 'admin/logistics/mall',
                        'icon' => 'icon-settings',
                    ],
                    [
                        'name' => \Yii::t('common', '全局价格'),
                        'route' => 'admin/logistics/price',
                        'icon' => 'icon-settings',
                    ],
                ]
            ],
            [
                'name' => \Yii::t('common', '清理缓存'),
                'route' => 'admin/cache/clean',
                'icon' => 'icon-qinglihuancun',
                'params' => [
                    '_layout' => 'admin',
                ],
            ],
            [
                'name' => \Yii::t('common', '插件中心'),
                'route' => 'admin/material/clean',
                'key' => 'material',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => \Yii::t('common', '素材管理'),
                        'route' => 'admin/material/index',
                        'icon' => 'icon-settings',
                    ],
                ]
            ],
        ];
    }
}
