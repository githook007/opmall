<?php
return [  // jayi-多语言
    'translations' => [
        'admin*' => [  // 管理中心
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'admin/user' => 'admin/user.php',
                'admin/common' => 'admin/common.php',
                'admin/app' => 'admin/app.php',
                'admin/cache' => 'admin/cache.php',
                'admin/mall' => 'admin/mall.php',
                'admin/passport' => 'admin/passport.php',
                'admin/setting' => 'admin/setting.php',
                'admin/update' => 'admin/update.php',
                'admin/app_manage' => 'admin/app_manage.php',
            ],
        ],
        'common*' => [  // 公共
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'common' => 'common.php',
            ],
        ],
        'plugins*' => [  // 插件中心
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'plugins/advance' => 'plugins/advance.php',
                'plugins/aliapp' => 'plugins/aliapp.php',
                'plugins/app' => 'plugins/app.php',
                'plugins/app_admin' => 'plugins/app_admin.php',
                'plugins/assistant' => 'plugins/assistant.php',
                'plugins/bargain' => 'plugins/bargain.php',
                'plugins/bdapp' => 'plugins/bdapp.php',
                'plugins/bonus' => 'plugins/bouns.php',
                'plugins/booking' => 'plugins/booking.php',
                'plugins/check_in' => 'plugins/check_in.php',
                'plugins/clerk' => 'plugins/clerk.php',
                'plugins/community' => 'plugins/community.php',
                'plugins/composition' => 'plugins/composition.php',
                'plugins/demo' => 'plugins/demo.php',
                'plugins/dianqilai' => 'plugins/dianqilai.php',
                'plugins/diy' => 'plugins/diy.php',
                'plugins/ecard' => 'plugins/ecard.php',
                'plugins/exchange' => 'plugins/exchange.php',
                'plugins/flash_sale' => 'plugins/flash_sale.php',
                'plugins/fxhb' => 'plugins/fxhb.php',
                'plugins/gift' => 'plugins/gift.php',
                'plugins/integral_mall' => 'plugins/integral_mall.php',
                'plugins/invoice' => 'plugins/invoice.php',
                'plugins/lottery' => 'plugins/lottery.php',
                'plugins/mch' => 'plugins/mch.php',
                'plugins/miaosha' => 'plugins/miaosha.php',
                'plugins/mobile' => 'plugins/mobile.php',
                'plugins/pick' => 'plugins/pick.php',
                'plugins/pintuan' => 'plugins/pintuan.php',
                'plugins/pond' => 'plugins/pond.php',
                'plugins/quick_share' => 'plugins/quick_share.php',
                'plugins/region' => 'plugins/region.php',
                'plugins/scan_code_pay' => 'plugins/scan_code_pay.php',
                'plugins/scratch' => 'plugins/scratch.php',
                'plugins/shopping' => 'plugins/shopping.php',
                'plugins/step' => 'plugins/step.php',
                'plugins/stock' => 'plugins/stock.php',
                'plugins/teller' => 'plugins/teller.php',
                'plugins/ttapp' => 'plugins/ttapp.php',
                'plugins/vip_card' => 'plugins/vip_card.php',
                'plugins/wechat' => 'plugins/wechat.php',
                'plugins/wholesale' => 'plugins/wholesale.php',
                'plugins/wxapp' => 'plugins/wxapp.php',
                'plugins/minishop' => 'plugins/minishop.php',
            ],
        ],
        'cloud*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'cloud/index' => 'cloud/index.php',
            ],
        ],
        'components*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'components/admin' => 'components/admin.php',
                'components/diy' => 'components/diy.php',
                'components/goods' => 'components/goods.php',
                'components/order' => 'components/order.php',
                'components/poster' => 'components/poster.php',
                'components/refund' => 'components/refund.php',
                'components/share' => 'components/share.php',
                'components/statistics' => 'components/statistics.php',
                'components/teller' => 'components/teller.php',
                'components/other' => 'components/other.php',
            ],
        ],
        'demo*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'demo/demo' => 'demo/demo.php',
            ],
        ],
        'error*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'error/error' => 'error/error.php',
            ],
        ],
        'install*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'install/install' => 'install/install.php',
            ],
        ],
        'layouts*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'layouts/layouts' => 'layouts/layouts.php',
            ],
        ],
        'pc*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'pc/banner' => 'pc/banner.php',
                'pc/index' => 'pc/index.php',
                'pc/nav' => 'pc/nav.php',
            ],
        ],
        'mall*' => [  // views
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'mall/app_page' => 'mall/app_page.php',
                'mall/article' => 'mall/article.php',
                'mall/assistant' => 'mall/assistant.php',
                'mall/attachment' => 'mall/attachment.php',
                'mall/bargain_statistics' => 'mall/bargain_statistics.php',
                'mall/booking_statistics' => 'mall/booking_statistics.php',
                'mall/card' => 'mall/card.php',
                'mall/cat' => 'mall/cat.php',
                'mall/city_service' => 'mall/city_service.php',
                'mall/clerk' => 'mall/clerk.php',
                'mall/community_statistics' => 'mall/community_statistics.php',
                'mall/composition_statistics' => 'mall/composition_statistics.php',
                'mall/copyright' => 'mall/copyright.php',
                'mall/coupon' => 'mall/coupon.php',
                'mall/coupon_auto_send' => 'mall/coupon_auto_send.php',
                'mall/data_statistics' => 'mall/data_statistics.php',
                'mall/delivery' => 'mall/delivery.php',
                'mall/demo' => 'mall/demo.php',
                'mall/express' => 'mall/express.php',
                'mall/file' => 'mall/file.php',
                'mall/finance' => 'mall/finance.php',
                'mall/flash_sale_statistics' => 'mall/flash_sale_statistics.php',
                'mall/free_delivery_rules' => 'mall/free_delivery_rules.php',
                'mall/full_reduce' => 'mall/full_reduce.php',
                'mall/fxhb_statistics' => 'mall/fxhb_statistics.php',
                'mall/gift_statistics' => 'mall/gift_statistics.php',
                'mall/goods' => 'mall/goods.php',
                'mall/goods_attr_template' => 'mall/goods_attr_template.php',
                'mall/goods_params_template' => 'mall/goods_params_template.php',
                'mall/home_block' => 'mall/home_block.php',
                'mall/home_nav' => 'mall/home_nav.php',
                'mall/home_page' => 'mall/home_page.php',
                'mall/import' => 'mall/import.php',
                'mall/index' => 'mall/index.php',
                'mall/integral_statistics' => 'mall/integral_statistics.php',
                'mall/live' => 'mall/live.php',
                'mall/lottery_statistics' => 'mall/lottery_statistics.php',
                'mall/mall_banner' => 'mall/mall_banner.php',
                'mall/mall_member' => 'mall/mall_member.php',
                'mall/material' => 'mall/material.php',
                'mall/mch' => 'mall/mch.php',
                'mall/miaosha_statistics' => 'mall/miaosha_statistics.php',
                'mall/navbar' => 'mall/navbar.php',
                'mall/notice' => 'mall/notice.php',
                'mall/offer_price' => 'mall/offer_price.php',
                'mall/order' => 'mall/order.php',
                'mall/order_comment_template' => 'mall/order_comment_template.php',
                'mall/order_comments' => 'mall/order_comments.php',
                'mall/order_form' => 'mall/order_form.php',
                'mall/order_send_template' => 'mall/order_send_template.php',
                'mall/order_statistics' => 'mall/order_statistics.php',
                'mall/page' => 'mall/page.php',
                'mall/page_title' => 'mall/page_title.php',
                'mall/pay_type' => 'mall/pay_type.php',
                'mall/pick_statistics' => 'mall/pick_statistics.php',
                'mall/pintuan_statistics' => 'mall/pintuan_statistics.php',
                'mall/plugin' => 'mall/plugin.php',
                'mall/postage_rule' => 'mall/postage_rule.php',
                'mall/poster' => 'mall/poster.php',
                'mall/price_statistics' => 'mall/price_statistics.php',
                'mall/printer' => 'mall/printer.php',
                'mall/qyick_shop' => 'mall/qyick_shop.php',
                'mall/recharge' => 'mall/recharge.php',
                'mall/refund_address' => 'mall/refund_address.php',
                'mall/region_statistics' => 'mall/region_statistics.php',
                'mall/role' => 'mall/role.php',
                'mall/role_setting' => 'mall/role_setting.php',
                'mall/role_user' => 'mall/role_user.php',
                'mall/send_statistics' => 'mall/send_statistics.php',
                'mall/service' => 'mall/service.php',
                'mall/share' => 'mall/share.php',
                'mall/share_statistics' => 'mall/share_statistics.php',
                'mall/sms' => 'mall/sms.php',
                'mall/statistic' => 'mall/statistic.php',
                'mall/step_statistic' => 'mall/step_statistic.php',
                'mall/stock_statistic' => 'mall/stock_statistic.php',
                'mall/store' => 'mall/store.php',
                'mall/template_msg' => 'mall/template_msg.php',
                'mall/territorial_limitation' => 'mall/territorial_limitation.php',
                'mall/theme_color' => 'mall/theme_color.php',
                'mall/topic' => 'mall/topic.php',
                'mall/topic_type' => 'mall/topic_type.php',
                'mall/tutorial' => 'mall/tutorial.php',
                'mall/user' => 'mall/user.php',
                'mall/user_center' => 'mall/user_center.php',
                'mall/video' => 'mall/video.php',
                'mall/we7' => 'mall/we7.php',
                'mall/we7_entry' => 'mall/we7_entry.php',
                'mall/wholesale_statistics' => 'mall/wholesale_statistics.php',
                'mall/wechat' => 'mall/wechat.php',
            ],
        ],
    ],
];