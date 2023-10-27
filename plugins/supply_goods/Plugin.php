<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */

namespace app\plugins\supply_goods;

// 使用插件加sql:alter table op_goods_warehouse add `supply_id` int(11) DEFAULT '0' COMMENT '货源商品id';
class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        $sourceType = \Yii::$app->session['sourceType'];
        $menu = [
            [
                'name' => \Yii::t('plugins/supply_goods', '货源设置'),
                'route' => 'plugin/supply_goods/mall/index/setting',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/supply_goods', '选择角色'),
                'route' => 'plugin/supply_goods/mall/index/index',
                'icon' => 'el-icon-star-on',
            ],
        ];
        if ($sourceType == 3){  // 批发商申请
            $menu = array_merge($menu, [
                [
                    'name' => \Yii::t('plugins/supply_goods', '申请成为批发商'),
                    'route' => 'plugin/supply_goods/mall/wholesaler/edit',
                    'icon' => 'el-icon-star-on',
                ]
            ]);
        } else if ($sourceType == 4){  // 批发商审核中
            $menu = array_merge($menu, [
                [
                    'name' => \Yii::t('plugins/supply_goods', '批发商'),
                    'route' => 'plugin/supply_goods/mall/wholesaler/index',
                    'icon' => 'el-icon-star-on',
                ]
            ]);
        } else if ($sourceType == 1){  // 普通商户
            $menu = array_merge($menu, [
                [
                    'name' => \Yii::t('plugins/supply_goods', '货源市场'),
                    'route' => 'plugin/supply_goods/mall/mch-goods/mch-goods-list',
                    'icon' => 'el-icon-star-on',
                    'action' => [
                        [
                            'name' => '保存为我的',
                            'route' => 'plugin/supply_goods/mall/goods/edit',
                        ]
                    ]
                ],
                [
                    'name' => \Yii::t('plugins/supply_goods', '我的货源'),
                    'route' => 'plugin/supply_goods/mall/goods/index',
                    'icon' => 'el-icon-star-on',
                ],
                [
                    'name' => \Yii::t('plugins/supply_goods', '货源订单'),
                    'route' => 'plugin/supply_goods/mall/buy-order/index',
                    'icon' => 'el-icon-star-on',
                ],
            ]);
        }else if ($sourceType == 2){  // 批发商
            $menu = array_merge($menu, [
                [
                    'name' => \Yii::t('plugins/supply_goods', '批发商'),
                    'route' => 'plugin/supply_goods/mall/wholesaler/index',
                    'icon' => 'el-icon-star-on',
                ],
                [
                    'name' => \Yii::t('plugins/supply_goods', '我的货源'),
                    'route' => 'plugin/supply_goods/mall/mch-goods/mch-goods-list',
                    'icon' => 'el-icon-star-on',
                ]
            ]);
        }
        return $menu;
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'supply_goods';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/supply_goods', '货源中心');
    }

    public function getIndexRoute()
    {
        return 'plugin/supply_goods/mall/index/index';
    }
}
