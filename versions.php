<?php

return [
    '2.3.23' => function () {
        $sql = <<<EOF
alter table op_goods add `video_channel` text DEFAULT null COMMENT '视频号设置';
alter table op_order add `replace_user_id` int(10) DEFAULT 0 COMMENT '代付用户id';
EOF;
        sql_execute($sql);
    },
    '2.3.24' => function () {
        $sql = <<<EOF
alter table `op_user_info` drop column `source`;
alter table `op_goods_warehouse` drop column `supply_id`;
EOF;
        sql_execute($sql);
    },
    '2.3.26' => function () {
        $sql = <<<EOF
alter table op_mail_setting add `send_platform` varchar(200) DEFAULT 'smtp.qq.com' COMMENT '发送平台';
EOF;
        sql_execute($sql);
    },
    '2.3.27' => function () {
        $sql = <<<EOF
CREATE TABLE `op_page_intro` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mall_id` int(11) NOT NULL COMMENT '商城',
  `route` varchar(250) NOT NULL COMMENT '页面路径',
  `super_content` longtext DEFAULT NULL COMMENT '超管员介绍内容',
  `manage_content` longtext DEFAULT NULL COMMENT '管理员介绍内容',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mall_id` (`mall_id`),
  KEY `route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页面介绍';
alter table op_page_intro add `content` longtext DEFAULT NULL COMMENT '介绍内容';
alter table `op_page_intro` drop column `super_content`;
alter table `op_page_intro` drop column `manage_content`;
alter table op_admin_info add `show_introduce_text` tinyint(1) DEFAULT 0 COMMENT '显示介绍富文本；1是';
EOF;
        sql_execute($sql);
    },
    '2.3.28' => function () {
        $sql = <<<EOF
alter table op_page_intro add `content` longtext DEFAULT NULL COMMENT '介绍内容';
alter table `op_page_intro` drop column `super_content`;
alter table `op_page_intro` drop column `manage_content`;
alter table op_admin_info add `show_introduce_text` tinyint(1) DEFAULT 0 COMMENT '显示介绍富文本；1是';
EOF;
        sql_execute($sql);
    },
    '2.3.29' => function () {
        $sql = <<<EOF
CREATE TABLE `op_wlhulian_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mall_id` int(11) NOT NULL COMMENT '商城',
  `shop_id` varchar(150) DEFAULT '' COMMENT '店铺id',
  `balance` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `price_type` tinyint(1) default 1 COMMENT '价格类型；1：固定金额；2：百分比',
  `price_value` decimal(8, 2) default '0.00' COMMENT '价格',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mall_id` (`mall_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商城聚合配送数据';
alter table op_wlhulian_data add `delivery_supplier_list` varchar(400) DEFAULT '' COMMENT '运力集合';
alter table op_wlhulian_data add `industry_type` tinyint(1) DEFAULT '9' COMMENT '行业类型';

CREATE TABLE `op_wlhulian_wallet_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mall_id` int(11) NOT NULL COMMENT '商城',
  `user_id` int(11) NOT NULL COMMENT '操作的用户id',
  `order_no` varchar(150) DEFAULT '' COMMENT '订单号',
  `money` decimal(8,2) DEFAULT '0.00' COMMENT '操作金额',
  `type` tinyint(1) default 1 COMMENT '类型；1：充值；2：扣除',
  `balance` decimal(10,2) DEFAULT '0.00' COMMENT '商城余额',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mall_id` (`mall_id`),
  KEY `order_no` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='聚合配送钱包记录';
EOF;
        sql_execute($sql);
    },
    '2.3.30' => function () {
        $sql = <<<EOF
alter table `op_order` drop column `city_info`;
alter table `op_order` drop column `city_name`;
alter table `op_order` drop column `city_mobile`;
alter table op_payment_order_union add `is_profit_sharing` tinyint(1) DEFAULT 0 COMMENT '1：分账；0：否';
alter table `op_wxapp_platform` drop column `third_appid`;

CREATE TABLE `op_attachment_effect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pic_id` int(11) NOT NULL COMMENT '图片id',
  `effect_id` int(11) NOT NULL COMMENT '效果图id',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pic_id` (`pic_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='图片效果列表';
EOF;
        sql_execute($sql);
    },
    '2.3.31' => function () {
        $sql = <<<EOF
alter table op_user_visit add `date` int(10) DEFAULT null COMMENT '日期';
alter table op_user_visit modify column `visit_uv_new` int(10) DEFAULT NULL COMMENT '新增用户留存';
alter table op_user_visit modify column `visit_uv` int(10) DEFAULT NULL COMMENT '活跃用户留存';
alter table op_user_visit modify column `time` int(10) DEFAULT NULL COMMENT '时间';
alter table op_user_visit add index (`mall_id`);
alter table op_user_visit add index (`date`);
EOF;
        sql_execute($sql);
    },
    '2.3.32' => function () {
        $sql = <<<EOF
DROP TABLE IF EXISTS `op_user_coupon_goods`;
alter table op_admin_info modify column `expired_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '账户过期时间';
alter table op_mall modify column `expired_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '账户过期时间';

CREATE TABLE `op_mall_extend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mall_id` int(11) NOT NULL COMMENT '商城',
  `goods_limit_num` int(11) DEFAULT -1 COMMENT '商品限制数量，-1代表无限制',
  `memory` int(10) NOT NULL DEFAULT 5120 COMMENT '总内存 -1为不限制，单位M',
  `used_memory` float(16,8) NOT NULL DEFAULT 0 COMMENT '已使用内存，单位M',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mall_id` (`mall_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商城扩展信息';
ALTER TABLE `op_recharge` ADD COLUMN `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型' AFTER `send_member_id`, ADD COLUMN `send_card` longtext NULL COMMENT '赠送卡券' AFTER `send_type`, ADD COLUMN `send_coupon` longtext NULL COMMENT '赠送优惠券' AFTER `send_card`;
ALTER TABLE `op_recharge_orders` ADD COLUMN `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型' AFTER `send_member_id`, ADD COLUMN `send_card` longtext NULL COMMENT '赠送卡券' AFTER `send_type`, ADD COLUMN `send_coupon` longtext NULL COMMENT '赠送优惠券' AFTER `send_card`;
ALTER TABLE `op_attachment_effect` ADD COLUMN `tag` varchar(30) DEFAULT NULL COMMENT '定位标签' AFTER `effect_id`;
EOF;
        sql_execute($sql);
        try {
            Yii::$app->queue->delay(0)->push(new \app\jobs\JavascriptJob(['name' => 'javascript/handle1']));
        }catch (Exception $e){
            Yii::error($e);
        }
    },
    '2.3.33' => function () {
        $sql = <<<EOF
ALTER TABLE `op_payment_order_union` ADD COLUMN `transaction_id` varchar(64) DEFAULT '' COMMENT '支付单号';

CREATE TABLE `op_order_trade_manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mall_id` int(11) NOT NULL,
  `payment_order_union_id` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL COMMENT '商品描述',
  `merchant_id` varchar(20) DEFAULT '' COMMENT '支付商户号',
  `sub_merchant_id` varchar(20) DEFAULT '' COMMENT '二级商户号',
  `trade_create_time` varchar(20) DEFAULT '' COMMENT '交易创建时间',
  `openid` varchar(50) DEFAULT '' COMMENT '支付者openid。',
  `pay_time` varchar(20) DEFAULT '' COMMENT '支付时间',
  `order_state` int(11) NOT NULL COMMENT '订单状态枚举：(1) 待发货；(2) 已发货；(3) 确认收货；(4) 交易完成；(5) 已退款。',
  `in_complaint` tinyint(1) DEFAULT '0' COMMENT '是否处在交易纠纷中。0否',
  `shipping` longtext DEFAULT null COMMENT '发货信息',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mall_id` (`mall_id`),
  KEY `payment_order_union_id` (`payment_order_union_id`),
  KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信发货管理';

ALTER TABLE `op_order_trade_manage` ADD COLUMN `transaction_id` varchar(50) DEFAULT NULL COMMENT '微信交易单号' AFTER `payment_order_union_id`;
ALTER TABLE `op_order_trade_manage` ADD COLUMN `merchant_trade_no` varchar(50) DEFAULT NULL COMMENT '商户订单号' AFTER `transaction_id`;
EOF;
        sql_execute($sql);
    }
];
