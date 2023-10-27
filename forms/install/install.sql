SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- ----------------------------
-- Table structure for op_address
-- ----------------------------
DROP TABLE IF EXISTS `op_address`;
CREATE TABLE `op_address`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `user_id`     int(11) NOT NULL,
    `name`        varchar(255)  NOT NULL COMMENT '收货人',
    `province_id` int(11) NOT NULL DEFAULT '0',
    `province`    varchar(255)  NOT NULL DEFAULT '' COMMENT '省份名称',
    `city_id`     int(11) NOT NULL DEFAULT '0',
    `city`        varchar(255)  NOT NULL DEFAULT '' COMMENT '城市名称',
    `district_id` int(11) NOT NULL DEFAULT '0',
    `district`    varchar(255)  NOT NULL DEFAULT '' COMMENT '县区名称',
    `mobile`      varchar(255)  NOT NULL COMMENT '联系电话',
    `detail`      varchar(1000) NOT NULL COMMENT '详细地址',
    `is_default`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp     NOT NULL,
    `deleted_at`  timestamp     NOT NULL,
    `latitude`    varchar(255)  NOT NULL DEFAULT '' COMMENT '经度',
    `longitude`   varchar(255)  NOT NULL DEFAULT '' COMMENT '纬度',
    `location`    varchar(255)  NOT NULL DEFAULT '' COMMENT '位置',
    `type`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：0快递 1同城',
    `sign`        varchar(100)           DEFAULT '' COMMENT '地址标签',
    PRIMARY KEY (`id`),
    KEY           `user_id` (`user_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户收货地址';

-- ----------------------------
-- Table structure for op_admin_info
-- ----------------------------
DROP TABLE IF EXISTS `op_admin_info`;
CREATE TABLE `op_admin_info`
(
    `id`                    int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`               int(11) NOT NULL,
    `app_max_count`         int(11) NOT NULL DEFAULT '-1' COMMENT '创建小程序最大数量-1.无限制',
    `permissions`           text         NOT NULL COMMENT '账户权限',
    `remark`                varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    `expired_at`            datetime     NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '账户过期时间',
    `is_delete`             int (11) NOT NULL DEFAULT '0',
    `we7_user_id`           int(11) NOT NULL COMMENT '默认填0',
    `is_default`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用默认权限',
    `secondary_permissions` longtext COMMENT '二级权限',
    `avatar`                varchar(255)          DEFAULT NULL COMMENT '头像',
    `show_introduce_text`   tinyint(1) DEFAULT 0 COMMENT '显示介绍富文本；1是',
    PRIMARY KEY (`id`),
    KEY                     `user_id` (`user_id`),
    KEY                     `is_delete` (`is_delete`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_admin_notice
-- ----------------------------
DROP TABLE IF EXISTS `op_admin_notice`;
CREATE TABLE `op_admin_notice`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) NOT NULL DEFAULT '0',
    `type`       varchar(20) NOT NULL DEFAULT '' COMMENT 'update更新urgent紧急important重要',
    `content`    text        NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_admin_register
-- ----------------------------
DROP TABLE IF EXISTS `op_admin_register`;
CREATE TABLE `op_admin_register`
(
    `id`                int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username`          varchar(255)  NOT NULL DEFAULT '' COMMENT '用户名',
    `password`          varchar(255)  NOT NULL DEFAULT '' COMMENT '密码',
    `mobile`            varchar(255)  NOT NULL DEFAULT '' COMMENT '手机号',
    `name`              varchar(45)   NOT NULL DEFAULT '' COMMENT '姓名/企业名',
    `remark`            varchar(255)  NOT NULL DEFAULT '' COMMENT '申请原因',
    `wechat_id`         varchar(64)   NOT NULL DEFAULT '' COMMENT '微信号',
    `id_card_front_pic` varchar(2000) NOT NULL DEFAULT '' COMMENT '身份证正面',
    `id_card_back_pic`  varchar(2000) NOT NULL DEFAULT '' COMMENT '身份证反面',
    `business_pic`      varchar(2000) NOT NULL DEFAULT '' COMMENT '营业执照',
    `status`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态：0=待审核，1=通过，2=不通过',
    `created_at`        timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp     NOT NULL,
    `deleted_at`        timestamp     NOT NULL,
    `is_delete`         tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                 `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_advance_banner
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_banner`;
CREATE TABLE `op_advance_banner`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `banner_id`  int(11) NOT NULL,
    `mall_id`    int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='预售轮播图';

-- ----------------------------
-- Table structure for op_advance_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_goods`;
CREATE TABLE `op_advance_goods`
(
    `id`                  int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`            int(11) NOT NULL,
    `mall_id`             int(11) NOT NULL,
    `ladder_rules`        varchar(4096) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '阶梯规则',
    `deposit`             decimal(10, 2)                   NOT NULL DEFAULT '0.00',
    `swell_deposit`       decimal(10, 2)                   NOT NULL DEFAULT '0.00' COMMENT '定金膨胀金',
    `start_prepayment_at` timestamp                        NOT NULL COMMENT '预售开始时间',
    `end_prepayment_at`   timestamp                        NOT NULL COMMENT '预售结束时间',
    `pay_limit`           int(11) NOT NULL COMMENT '尾款支付时间 -1:无限制',
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                   `goods_id` (`goods_id`),
    KEY                   `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_advance_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_goods_attr`;
CREATE TABLE `op_advance_goods_attr`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `deposit`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '商品所需定金',
    `swell_deposit` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '定金膨胀金',
    `goods_id`      int(11) NOT NULL,
    `goods_attr_id` int(11) NOT NULL,
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    `advance_num`   int(11) NOT NULL DEFAULT '0' COMMENT '预约数量',
    PRIMARY KEY (`id`),
    KEY             `goods_id` (`goods_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_advance_order
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_order`;
CREATE TABLE `op_advance_order`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `user_id`            int(11) NOT NULL,
    `goods_id`           int(11) NOT NULL COMMENT '商品ID',
    `goods_attr_id`      int(11) NOT NULL COMMENT '规格ID',
    `goods_num`          int(11) NOT NULL DEFAULT '0',
    `order_id`           int(11) NOT NULL DEFAULT '0',
    `order_no`           varchar(255)   NOT NULL DEFAULT '0',
    `advance_no`         varchar(255)   NOT NULL COMMENT '定金订单号',
    `deposit`            decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '定金',
    `swell_deposit`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '膨胀金',
    `is_cancel`          tinyint(2) NOT NULL DEFAULT '0' COMMENT '1取消',
    `cancel_time`        timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_refund`          tinyint(2) NOT NULL DEFAULT '0' COMMENT '1退款',
    `is_delete`          tinyint(2) NOT NULL DEFAULT '0' COMMENT '1删除',
    `is_pay`             tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否支付：0.未支付|1.已支付',
    `is_recycle`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0.否|1.是',
    `pay_type`           tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付',
    `pay_time`           timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `remark`             varchar(255)   NOT NULL DEFAULT '' COMMENT '备注',
    `auto_cancel_time`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '自动取消时间',
    `created_at`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `goods_info`         longtext       NOT NULL,
    `token`              varchar(32)    NOT NULL,
    `order_token`        varchar(32)             DEFAULT NULL,
    `preferential_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '活动优惠金额',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `user_id` (`user_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_advance_order_submit_result
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_order_submit_result`;
CREATE TABLE `op_advance_order_submit_result`
(
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(32) NOT NULL,
    `data`  longtext,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_advance_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_advance_setting`;
CREATE TABLE `op_advance_setting`
(
    `id`                        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `is_advance`                tinyint(1) NOT NULL DEFAULT '1',
    `payment_type`              text         NOT NULL,
    `deposit_payment_type`      varchar(255) NOT NULL DEFAULT '',
    `is_share`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                    tinyint(1) NOT NULL DEFAULT '0',
    `is_mail`                   tinyint(1) NOT NULL DEFAULT '0',
    `is_print`                  tinyint(1) NOT NULL DEFAULT '0',
    `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启区域允许购买',
    `goods_poster`              longtext     NOT NULL,
    `send_type`                 varchar(255) NOT NULL DEFAULT '' COMMENT '发货方式',
    `over_time`                 int(11) NOT NULL DEFAULT '0' COMMENT '未支付定金订单超时时间',
    `created_at`                timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                timestamp    NOT NULL,
    `deleted_at`                timestamp    NOT NULL,
    `is_delete`                 tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`),
    KEY                         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_aliapp_config
-- ----------------------------
DROP TABLE IF EXISTS `op_aliapp_config`;
CREATE TABLE `op_aliapp_config`
(
    `id`                         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                    int(11) NOT NULL,
    `appid`                      varchar(32)   NOT NULL,
    `app_private_key`            varchar(2000) NOT NULL,
    `alipay_public_key`          varchar(2000) NOT NULL,
    `cs_tnt_inst_id`             varchar(32)   NOT NULL DEFAULT '',
    `cs_scene`                   varchar(32)   NOT NULL DEFAULT '',
    `app_aes_secret`             varchar(32)   NOT NULL DEFAULT '' COMMENT '内容加密AES密钥',
    `transfer_app_id`            varchar(64)            DEFAULT '' COMMENT '打款到用户app_id',
    `transfer_app_private_key`   varchar(2048)          DEFAULT '' COMMENT '打款到用户app_private_key',
    `transfer_alipay_public_key` text,
    `transfer_appcert`           text COMMENT '应用公钥证书',
    `transfer_alipay_rootcert`   text COMMENT '支付宝根证书',
    `created_at`                 timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                 timestamp     NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY                          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_aliapp_template
-- ----------------------------
DROP TABLE IF EXISTS `op_aliapp_template`;
CREATE TABLE `op_aliapp_template`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(255) NOT NULL,
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_article
-- ----------------------------
DROP TABLE IF EXISTS `op_article`;
CREATE TABLE `op_article`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL DEFAULT '0',
    `article_cat_id` int(11) NOT NULL COMMENT '分类id：1=关于我们，2=服务中心 , 3=拼团',
    `status`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '0隐藏 1显示',
    `title`          varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `content`        longtext     NOT NULL COMMENT '内容',
    `sort`           int(11) NOT NULL DEFAULT '0' COMMENT '排序',
    `is_delete`      smallint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at`     timestamp    NOT NULL COMMENT '删除时间',
    `created_at`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`     timestamp    NOT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY              `store_id` (`mall_id`) USING BTREE,
    KEY              `is_delete` (`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_assistant_data
-- ----------------------------
DROP TABLE IF EXISTS `op_assistant_data`;
CREATE TABLE `op_assistant_data`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `type`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0--淘宝 1--淘宝app 2--天猫 3--天猫app 4--京东 5--拼多多',
    `itemId`     varchar(255) NOT NULL DEFAULT '0' COMMENT '原始商品id',
    `json`       longtext     NOT NULL COMMENT '数据',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='采集助手数据存储';

-- ----------------------------
-- Table structure for op_attachment
-- ----------------------------
DROP TABLE IF EXISTS `op_attachment`;
CREATE TABLE `op_attachment`
(
    `id`                  int(10) unsigned NOT NULL AUTO_INCREMENT,
    `storage_id`          int(11) NOT NULL,
    `attachment_group_id` int(11) NOT NULL DEFAULT '0',
    `user_id`             int(11) NOT NULL,
    `mall_id`             int(11) NOT NULL DEFAULT '0',
    `mch_id`              int(11) NOT NULL DEFAULT '0' COMMENT '多商户id',
    `name`                varchar(128)  NOT NULL,
    `size`                int(11) NOT NULL COMMENT '大小：字节',
    `url`                 varchar(2080) NOT NULL,
    `thumb_url`           varchar(2080) NOT NULL DEFAULT '',
    `type`                tinyint(2) NOT NULL COMMENT '类型：1=图片，2=视频',
    `created_at`          timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          timestamp     NOT NULL,
    `deleted_at`          timestamp     NOT NULL,
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0',
    `is_recycle`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0.否|1.是',
    PRIMARY KEY (`id`),
    KEY                   `attachment_group_id` (`attachment_group_id`),
    KEY                   `mall_id` (`mall_id`),
    KEY                   `mch_id` (`mch_id`),
    KEY                   `type` (`type`),
    KEY                   `is_delete` (`is_delete`),
    KEY                   `is_recycle` (`is_recycle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件、文件';

-- ----------------------------
-- Table structure for op_attachment_group
-- ----------------------------
DROP TABLE IF EXISTS `op_attachment_group`;
CREATE TABLE `op_attachment_group`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(64) NOT NULL,
    `is_delete`  smallint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_recycle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0.否|1.是',
    `type`       tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 图片 1商品',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`),
    KEY          `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_attachment_storage
-- ----------------------------
DROP TABLE IF EXISTS `op_attachment_storage`;
CREATE TABLE `op_attachment_storage`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `type`       tinyint(1) NOT NULL DEFAULT '1' COMMENT '存储类型：1=本地，2=阿里云，3=腾讯云，4=七牛',
    `config`     longtext NOT NULL COMMENT '存储配置',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=未启用，1=已启用',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `user_id`    int(11) NOT NULL DEFAULT '1' COMMENT '存储设置所属账号',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件存储器';

-- ----------------------------
-- Table structure for op_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `op_auth_role`;
CREATE TABLE `op_auth_role`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `creator_id` int(11) NOT NULL COMMENT '创建者ID',
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(64)  NOT NULL DEFAULT '',
    `remark`     varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述、备注',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for op_auth_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `op_auth_role_permission`;
CREATE TABLE `op_auth_role_permission`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `role_id`     int(11) NOT NULL,
    `permissions` longtext NOT NULL,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='角色和权限的关联组';

-- ----------------------------
-- Table structure for op_auth_role_user
-- ----------------------------
DROP TABLE IF EXISTS `op_auth_role_user`;
CREATE TABLE `op_auth_role_user`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `role_id`   int(11) NOT NULL,
    `user_id`   int(11) NOT NULL,
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户角色关联组';

-- ----------------------------
-- Table structure for op_balance_log
-- ----------------------------
DROP TABLE IF EXISTS `op_balance_log`;
CREATE TABLE `op_balance_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        tinyint(1) NOT NULL COMMENT '类型：1=收入，2=支出',
    `money`       decimal(10, 2) NOT NULL COMMENT '变动金额',
    `desc`        varchar(255)   NOT NULL DEFAULT '' COMMENT '变动说明',
    `custom_desc` longtext       NOT NULL COMMENT '自定义详细说明|记录',
    `order_no`    varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `created_at`  timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `user_id` (`user_id`),
    KEY           `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_banner
-- ----------------------------
DROP TABLE IF EXISTS `op_banner`;
CREATE TABLE `op_banner`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `pic_url`    varchar(2080) NOT NULL COMMENT '图片',
    `title`      varchar(255)  NOT NULL DEFAULT '' COMMENT '标题',
    `page_url`   varchar(2048) NOT NULL DEFAULT '' COMMENT '页面路径',
    `open_type`  varchar(65)   NOT NULL DEFAULT '' COMMENT '打开方式',
    `params`     text          NOT NULL COMMENT '导航参数',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `deleted_at` timestamp     NOT NULL COMMENT '删除时间',
    `updated_at` timestamp     NOT NULL COMMENT '修改时间',
    `sign`       varchar(65)   NOT NULL DEFAULT '' COMMENT '插件标识',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_bargain_banner
-- ----------------------------
DROP TABLE IF EXISTS `op_bargain_banner`;
CREATE TABLE `op_bargain_banner`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `banner_id`  int(11) NOT NULL,
    `mall_id`    int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='砍价轮播图';

-- ----------------------------
-- Table structure for op_bargain_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_bargain_goods`;
CREATE TABLE `op_bargain_goods`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `goods_id`        int(11) NOT NULL,
    `mall_id`         int(11) NOT NULL,
    `min_price`       decimal(11, 2)                  NOT NULL DEFAULT '0.00' COMMENT '最低价',
    `begin_time`      timestamp                       NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',
    `end_time`        timestamp                       NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',
    `time`            int(11) NOT NULL DEFAULT '0' COMMENT '砍价小时数',
    `status_data`     varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '砍价方式数据',
    `is_delete`       smallint(6) NOT NULL DEFAULT '0',
    `created_at`      timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`      timestamp                       NOT NULL,
    `updated_at`      timestamp                       NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `status`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动是否开放 0--不开放 1--开放',
    `type`            tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许中途下单 1--允许 2--不允许',
    `stock`           int(11) NOT NULL DEFAULT '0' COMMENT '活动库存',
    `initiator`       int(11) NOT NULL DEFAULT '0' COMMENT '发起人数',
    `participant`     int(11) NOT NULL DEFAULT '0' COMMENT '参与人数',
    `min_price_goods` int(11) NOT NULL DEFAULT '0' COMMENT '砍到最小价格数',
    `underway`        int(11) NOT NULL DEFAULT '0' COMMENT '进行中的',
    `success`         int(11) NOT NULL DEFAULT '0' COMMENT '成功的',
    `fail`            int(11) NOT NULL DEFAULT '0' COMMENT '失败的',
    `stock_type`      tinyint(1) NOT NULL DEFAULT '1' COMMENT '减库存的方式 1--参与减库存 2--拍下减库存',
    PRIMARY KEY (`id`),
    UNIQUE KEY `goods_id` (`goods_id`) USING BTREE,
    KEY               `mall_id` (`mall_id`),
    KEY               `is_delete` (`is_delete`),
    KEY               `end_time` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='砍价商品设置';

-- ----------------------------
-- Table structure for op_bargain_order
-- ----------------------------
DROP TABLE IF EXISTS `op_bargain_order`;
CREATE TABLE `op_bargain_order`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `user_id`            int(11) NOT NULL,
    `bargain_goods_id`   int(11) NOT NULL COMMENT '砍价商品id',
    `token`              varchar(255)   NOT NULL,
    `price`              decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '售价',
    `min_price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '最低价',
    `time`               int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间',
    `status`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0--进行中 1--成功 2--失败',
    `bargain_goods_data` longtext       NOT NULL COMMENT '砍价设置',
    `created_at`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`          tinyint(1) NOT NULL,
    `preferential_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_bargain_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_bargain_setting`;
CREATE TABLE `op_bargain_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      longtext     NOT NULL,
    `created_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='砍价设置';

-- ----------------------------
-- Table structure for op_bargain_user_order
-- ----------------------------
DROP TABLE IF EXISTS `op_bargain_user_order`;
CREATE TABLE `op_bargain_user_order`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `user_id`          int(11) NOT NULL,
    `bargain_order_id` int(11) NOT NULL COMMENT '砍价订单ID',
    `price`            decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '砍价的金额',
    `is_delete`        tinyint(1) NOT NULL,
    `created_at`       timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `token`            varchar(255)   NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `user_id` (`user_id`),
    KEY                `bargain_order_id` (`bargain_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户参与砍价所砍的金额';

-- ----------------------------
-- Table structure for op_bdapp_config
-- ----------------------------
DROP TABLE IF EXISTS `op_bdapp_config`;
CREATE TABLE `op_bdapp_config`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `created_at`      timestamp NULL DEFAULT NULL,
    `updated_at`      timestamp NULL DEFAULT NULL,
    `app_id`          varchar(16) DEFAULT NULL,
    `app_key`         varchar(64) DEFAULT NULL,
    `app_secret`      varchar(64) DEFAULT NULL,
    `pay_dealid`      varchar(64) DEFAULT NULL,
    `pay_public_key`  text,
    `pay_private_key` text,
    `pay_app_key`     varchar(64) DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY               `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for op_bdapp_order
-- ----------------------------
DROP TABLE IF EXISTS `op_bdapp_order`;
CREATE TABLE `op_bdapp_order`
(
    `id`                 int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_no`           varchar(150)   NOT NULL DEFAULT '' COMMENT '订单号',
    `bd_user_id`         varchar(255)   NOT NULL DEFAULT '',
    `bd_order_id`        varchar(255)   NOT NULL DEFAULT '' COMMENT '百度平台订单ID',
    `bd_refund_batch_id` varchar(255)   NOT NULL DEFAULT '' COMMENT '百度平台退款批次号',
    `bd_refund_money`    int(11) NOT NULL DEFAULT '0',
    `refund_money`       decimal(10, 2) NOT NULL DEFAULT '0.00',
    `is_refund`          tinyint(4) NOT NULL DEFAULT '0',
    `created_at`         timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp      NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY                  `order_no` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='百度订单号与商城订单号关联表';

-- ----------------------------
-- Table structure for op_bdapp_template
-- ----------------------------
DROP TABLE IF EXISTS `op_bdapp_template`;
CREATE TABLE `op_bdapp_template`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(65)  NOT NULL DEFAULT '',
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_bonus_captain
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_captain`;
CREATE TABLE `op_bonus_captain`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `name`         varchar(32)    NOT NULL DEFAULT '' COMMENT '队长姓名',
    `mobile`       varchar(64)    NOT NULL DEFAULT '' COMMENT '队长手机',
    `user_id`      int(11) NOT NULL,
    `all_bonus`    decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '累计分红',
    `total_bonus`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '已分红',
    `expect_bonus` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '预计分红，未到账分红',
    `reason`       varchar(255)   NOT NULL DEFAULT '',
    `remark`       varchar(255)   NOT NULL DEFAULT '' COMMENT '描述',
    `level`        int(11) NOT NULL DEFAULT '0' COMMENT '会员等级:0. 普通成员 关联等级表',
    `status`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1重新申请未提交 0--申请中 1--成功 2--失败 3--处理中',
    `all_member`   int(11) NOT NULL DEFAULT '0' COMMENT '团员数量',
    `created_at`   timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp      NOT NULL,
    `deleted_at`   timestamp      NOT NULL,
    `apply_at`     timestamp NULL DEFAULT NULL,
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `user_id` (`user_id`) USING BTREE,
    KEY            `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='团队分红队长表';

-- ----------------------------
-- Table structure for op_bonus_captain_log
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_captain_log`;
CREATE TABLE `op_bonus_captain_log`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `handler`   int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
    `user_id`   int(11) NOT NULL COMMENT '队长',
    `event`     varchar(255) NOT NULL COMMENT '事件名',
    `content`   mediumtext   NOT NULL COMMENT '记录信息',
    `create_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `mall_id` (`mall_id`),
    KEY         `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='队长操作日志表';

-- ----------------------------
-- Table structure for op_bonus_captain_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_captain_relation`;
CREATE TABLE `op_bonus_captain_relation`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `captain_id` int(11) NOT NULL COMMENT '队长id',
    `user_id`    int(11) NOT NULL COMMENT '团队id',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`) USING BTREE,
    KEY          `is_delete` (`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_bonus_cash
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_cash`;
CREATE TABLE `op_bonus_cash`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `order_no`       varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
    `service_charge` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',
    `type`           varchar(255)   NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
    `extra`          longtext COMMENT '额外信息 例如微信账号、支付宝账号等',
    `status`         int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',
    `is_delete`      int(11) NOT NULL DEFAULT '0',
    `created_at`     datetime       NOT NULL,
    `updated_at`     datetime       NOT NULL,
    `deleted_at`     datetime       NOT NULL,
    `content`        longtext,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='提现记录表';

-- ----------------------------
-- Table structure for op_bonus_cash_log
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_cash_log`;
CREATE TABLE `op_bonus_cash_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        int(11) NOT NULL DEFAULT '1' COMMENT '类型 1--收入 2--支出',
    `price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '变动佣金',
    `desc`        longtext,
    `custom_desc` longtext,
    `is_delete`   int(11) NOT NULL DEFAULT '0',
    `created_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_bonus_members
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_members`;
CREATE TABLE `op_bonus_members`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `level`            int(11) unsigned NOT NULL COMMENT '等级',
    `name`             varchar(64) NOT NULL DEFAULT '' COMMENT '等级名称',
    `auto_update`      tinyint(1) NOT NULL COMMENT '是否开启自动升级',
    `update_type`      int(11) NOT NULL DEFAULT '0' COMMENT '升级条件类型',
    `update_condition` varchar(64) NOT NULL COMMENT '升级条件',
    `rate`             varchar(32) NOT NULL DEFAULT '0' COMMENT '分红比例',
    `status`           tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0--禁用 1--启用',
    `created_at`       timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp   NOT NULL,
    `deleted_at`       timestamp   NOT NULL,
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_bonus_order_log
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_order_log`;
CREATE TABLE `op_bonus_order_log`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL DEFAULT '0',
    `order_id`         int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
    `from_user_id`     int(11) NOT NULL DEFAULT '0' COMMENT '下单用户ID',
    `to_user_id`       int(11) NOT NULL DEFAULT '0' COMMENT '受益用户ID',
    `price`            decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '订单商品实付金额',
    `bonus_price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '分红金额',
    `fail_bonus_price` decimal(10, 2)          DEFAULT '0.00' COMMENT '失败分红金额',
    `status`           tinyint(2) NOT NULL DEFAULT '0' COMMENT '0预计分红，1完成分红，2分红失败',
    `created_at`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`        tinyint(2) NOT NULL DEFAULT '0',
    `remark`           varchar(200)            DEFAULT NULL COMMENT '备注',
    `bonus_rate`       varchar(32)    NOT NULL DEFAULT '0' COMMENT '下单时的分红比例%',
    PRIMARY KEY (`id`),
    UNIQUE KEY `order_id` (`order_id`) USING BTREE,
    KEY                `from_user_id` (`from_user_id`) USING BTREE,
    KEY                `to_user_id` (`to_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_bonus_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_bonus_setting`;
CREATE TABLE `op_bonus_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      text         NOT NULL,
    `created_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
    `is_delete`  int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',
    `deleted_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='团队分红设置';

-- ----------------------------
-- Table structure for op_booking_cats
-- ----------------------------
DROP TABLE IF EXISTS `op_booking_cats`;
CREATE TABLE `op_booking_cats`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `cat_id`     int(11) NOT NULL,
    `sort`       int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_booking_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_booking_goods`;
CREATE TABLE `op_booking_goods`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `goods_id`        int(11) NOT NULL,
    `form_data`       longtext  NOT NULL COMMENT '自定义表单',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp NOT NULL,
    `deleted_at`      timestamp NOT NULL,
    `is_order_form`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启自定义表单0.否|1.是',
    `order_form_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.选择表单|2.自定义表单',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `goods_id` (`goods_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_booking_order
-- ----------------------------
DROP TABLE IF EXISTS `op_booking_order`;
CREATE TABLE `op_booking_order`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `token`      varchar(255) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_booking_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_booking_setting`;
CREATE TABLE `op_booking_setting`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `is_share`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启短信通知',
    `is_mail`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启邮件通知',
    `is_print`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启订单打印',
    `is_cat`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示分类',
    `is_form`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用默认form',
    `form_data`    longtext  NOT NULL COMMENT 'form默认表单',
    `created_at`   timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp NOT NULL,
    `payment_type` longtext  NOT NULL COMMENT '支付方式',
    `goods_poster` longtext  NOT NULL COMMENT '自定义海报',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_booking_store
-- ----------------------------
DROP TABLE IF EXISTS `op_booking_store`;
CREATE TABLE `op_booking_store`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `store_id`   int(11) NOT NULL,
    `goods_id`   int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_cart
-- ----------------------------
DROP TABLE IF EXISTS `op_cart`;
CREATE TABLE `op_cart`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `goods_id`   int(11) NOT NULL COMMENT '商品',
    `attr_id`    int(11) NOT NULL COMMENT '商品规格',
    `num`        int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
    `mch_id`     int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `sign`       varchar(65) NOT NULL DEFAULT '',
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp   NOT NULL,
    `updated_at` timestamp   NOT NULL,
    `attr_info`  text,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_check_in_award_config
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_award_config`;
CREATE TABLE `op_check_in_award_config`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `number`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '奖励数量',
    `day`        int(11) NOT NULL DEFAULT '0' COMMENT '领取奖励的天数',
    `type`       varchar(255)   NOT NULL DEFAULT '' COMMENT '奖励类型integral--积分|balance--余额',
    `status`     tinyint(1) NOT NULL COMMENT '领取类型1--普通签到领取|2--连续签到领取|3--累计签到领取',
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='签到奖励设置';

-- ----------------------------
-- Table structure for op_check_in_config
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_config`;
CREATE TABLE `op_check_in_config`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `status`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启 0--关闭|1--开启',
    `is_remind`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒 0--关闭|1--开启',
    `time`          varchar(255) NOT NULL COMMENT '提醒时间',
    `continue_type` tinyint(1) NOT NULL COMMENT '连续签到周期1--不限|2--周清|3--月清',
    `rule`          longtext     NOT NULL COMMENT '签到规则',
    `is_delete`     tinyint(1) NOT NULL,
    `created_at`    timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`    timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`    timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='签到设置';

-- ----------------------------
-- Table structure for op_check_in_customize
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_customize`;
CREATE TABLE `op_check_in_customize`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) NOT NULL,
    `value`      longtext     NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_check_in_queue
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_queue`;
CREATE TABLE `op_check_in_queue`
(
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(150) NOT NULL,
    `data`  longtext     NOT NULL,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='签到定时任务执行记录表';

-- ----------------------------
-- Table structure for op_check_in_sign
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_sign`;
CREATE TABLE `op_check_in_sign`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `number`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '签到奖励数量',
    `type`       varchar(255)   NOT NULL DEFAULT '' COMMENT '签到奖励类型integral--积分|balance--余额',
    `day`        int(11) NOT NULL DEFAULT '1' COMMENT '签到天数',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '1--普通签到奖励 2--连续签到奖励 3--累计签到奖励',
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    `token`      varchar(255)   NOT NULL,
    `award_id`   int(11) NOT NULL DEFAULT '0' COMMENT '签到奖励id',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='签到领取奖励';

-- ----------------------------
-- Table structure for op_check_in_user
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_user`;
CREATE TABLE `op_check_in_user`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `total`          int(11) NOT NULL DEFAULT '0' COMMENT '累计签到时间',
    `continue`       int(11) NOT NULL DEFAULT '0' COMMENT '连续签到时间',
    `is_remind`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启签到提醒',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`      tinyint(1) NOT NULL,
    `updated_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`     timestamp NOT NULL,
    `continue_start` timestamp NULL DEFAULT NULL COMMENT '连续签到的起始日期',
    PRIMARY KEY (`id`),
    KEY              `user_id` (`user_id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='签到插件--用户表';

-- ----------------------------
-- Table structure for op_check_in_user_remind
-- ----------------------------
DROP TABLE IF EXISTS `op_check_in_user_remind`;
CREATE TABLE `op_check_in_user_remind`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `date`       timestamp NOT NULL,
    `is_remind`  tinyint(1) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `updated_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`),
    KEY          `is_remind` (`is_remind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户签到提醒记录';

-- ----------------------------
-- Table structure for op_city_deliveryman
-- ----------------------------
DROP TABLE IF EXISTS `op_city_deliveryman`;
CREATE TABLE `op_city_deliveryman`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(255) NOT NULL COMMENT '配送员名称',
    `mobile`     varchar(255) NOT NULL COMMENT '联系方式',
    `is_delete`  tinyint(1) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_city_delivery_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_city_delivery_setting`;
CREATE TABLE `op_city_delivery_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `key`        varchar(60) DEFAULT NULL,
    `value`      text,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  tinyint(2) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `key` (`key`),
    KEY          `is_delete` (`is_delete`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_city_preview_order
-- ----------------------------
DROP TABLE IF EXISTS `op_city_preview_order`;
CREATE TABLE `op_city_preview_order`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `result_data`       text,
    `order_info`        text,
    `created_at`        timestamp NULL DEFAULT NULL,
    `order_detail_sign` varchar(255) DEFAULT NULL,
    `all_order_info`    text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_city_service
-- ----------------------------
DROP TABLE IF EXISTS `op_city_service`;
CREATE TABLE `op_city_service`
(
    `id`                       int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                  int(11) NOT NULL,
    `platform`                 varchar(255) DEFAULT NULL COMMENT '所属平台',
    `name`                     varchar(255) NOT NULL COMMENT '配送名称',
    `distribution_corporation` int(11) NOT NULL COMMENT '配送公司 1.顺丰|2.闪送|3.美团配送|4.达达',
    `shop_no`                  varchar(255) DEFAULT NULL COMMENT '门店编号',
    `data`                     text,
    `created_at`               timestamp NULL DEFAULT NULL,
    `is_delete`                int(1) NOT NULL DEFAULT '0',
    `service_type`             varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY                        `mall_id` (`mall_id`),
    KEY                        `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_clerk_user
-- ----------------------------
DROP TABLE IF EXISTS `op_clerk_user`;
CREATE TABLE `op_clerk_user`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) NOT NULL,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_clerk_user_store_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_clerk_user_store_relation`;
CREATE TABLE `op_clerk_user_store_relation`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `clerk_user_id` int(11) NOT NULL,
    `store_id`      int(11) NOT NULL,
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    `created_at`    timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`    timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY             `clerk_user_id` (`clerk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_cloud_template
-- ----------------------------
DROP TABLE IF EXISTS `op_cloud_template`;
CREATE TABLE `op_cloud_template`
(
    `id`      int(11) NOT NULL AUTO_INCREMENT COMMENT '云模板ID',
    `name`    varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板名称',
    `pics`    text COLLATE utf8mb4_unicode_ci        NOT NULL COMMENT '云模板图片',
    `detail`  text COLLATE utf8mb4_unicode_ci        NOT NULL COMMENT '云模板详情',
    `price`   decimal(10, 2)                         NOT NULL COMMENT '云模板价格',
    `type`    varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板类型',
    `version` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '云模板版本',
    `package` text COLLATE utf8mb4_unicode_ci        NOT NULL COMMENT '云模板资源包',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='云模板';

-- ----------------------------
-- Records of op_cloud_template
-- ----------------------------
INSERT INTO `op_cloud_template`
VALUES ('1', '双十二',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/d66b72d485ceed26d358e8f142dec60f.png\"]',
        '双十二', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/6d79442a06563f9b356854994d794b9a.zip');
INSERT INTO `op_cloud_template`
VALUES ('2', '服饰1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/42d68cee10e3fa8af2c23ab81241e14e.png\"]',
        '服饰1', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/e7b0241ebaf1b6a62498d6b3368104d7.zip');
INSERT INTO `op_cloud_template`
VALUES ('3', '服饰2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/7e96634708ed70371f9fbcd3dcba0bb4.png\"]',
        '服饰2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/ab1744dc43d2086eb9c783bef3103a81.zip');
INSERT INTO `op_cloud_template`
VALUES ('4', '服饰3',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2e86a1ad42ac8ea8b5f9c8e1131e2cc2.png\"]',
        '服饰3', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/0f42e2f848b9f4f915e89bb143a07f08.zip');
INSERT INTO `op_cloud_template`
VALUES ('5', '生鲜1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/3d6b77eb7d512537c8e03e86c1e052f5.png\"]',
        '生鲜1', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/60cada7959c796057723077f5ece92b8.zip');
INSERT INTO `op_cloud_template`
VALUES ('6', '生鲜2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/7b516ba8fce669badea52aa2452dc3db.png\"]',
        '生鲜2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2d55951b4a6f9edfb3119f1a6523d85d.zip');
INSERT INTO `op_cloud_template`
VALUES ('7', '美妆1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/9d24aec77b45951b50343f3bc9708cd9.png\"]',
        '美妆1', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/7926abf6c6100457633685f5bc59375a.zip');
INSERT INTO `op_cloud_template`
VALUES ('8', '美妆2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/dc9370e6666b5cb588d81c1b22c72151.png\"]',
        '美妆2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/35f29d446dd3b6329affd943e0e9f5c4.zip');
INSERT INTO `op_cloud_template`
VALUES ('9', '超市1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/3bddad82d6c63870596334e318777fa5.png\"]',
        '超市1', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/33bd4dfe5ad7a707042a74b7bd79d843.zip');
INSERT INTO `op_cloud_template`
VALUES ('10', '超市2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/81ecb3a4ec8e8248da0e7f7a027bafd2.png\"]',
        '超市2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/87646dd04503f59f316e23af515015e8.zip');
INSERT INTO `op_cloud_template`
VALUES ('11', '超市3',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/63f989c9f7b9663e6ab9de13c03056b6.png\"]',
        '超市3', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/476d9d5bcc2b4faba2693b4f6c990603.zip');
INSERT INTO `op_cloud_template`
VALUES ('12', '春节模板',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2be7b8bd21b5b89c1008bcbfbd6fc876.png\"]',
        '春节模板', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/29434d36932147c538e575e95918ec3b.zip');
INSERT INTO `op_cloud_template`
VALUES ('13', '元宵节模板',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/a5c397ade39f0e76c784d46078f0ea20.png\"]',
        '元宵节模板', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4057bee3a03bcbe14d1c7d5fb36a5afb.zip');
INSERT INTO `op_cloud_template`
VALUES ('14', '情人节模板',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/615112ec2dc3e3b0b8aaa9b0b93bd4f0.png\"]',
        '情人节模板', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/f275c5db1cbd432567b7eaab81cf1dd0.zip');
INSERT INTO `op_cloud_template`
VALUES ('15', '春节模板2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2022fc32d157bbdacae989174c75d583.png\"]',
        '春节模板2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/732a5469e1f2110de1b0433ba9223a14.zip');
INSERT INTO `op_cloud_template`
VALUES ('16', '元宵节模板2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/5f8c2f51b4a9160decb00bed2277952f.png\"]',
        '元宵节模板2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/46fcb342159ea926a4a5258503717895.zip');
INSERT INTO `op_cloud_template`
VALUES ('17', '情人节模板2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/a0dadedd1a511bfa59fa63497a8c974d.png\"]',
        '情人节模板2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/ee6f185747fc4079bc097331ffe26e94.zip');
INSERT INTO `op_cloud_template`
VALUES ('18', '妇女节',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/be4f913235be3ca1b7a4b4d34bb45216.png\"]',
        '妇女节', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/aa6b10949760ec20e2febfe8598d28e1.zip');
INSERT INTO `op_cloud_template`
VALUES ('19', '妇女节2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/df3307934c25cfbf1ae2c1046037d8bc.png\"]',
        '妇女节2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/3d31d280d6bb8e87fdf433369bc25126.zip');
INSERT INTO `op_cloud_template`
VALUES ('20', '51劳动节',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/fdce3d73af8165e0f5652187213950e3.png\"]',
        '51劳动节', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/976637b958d269cd483784e9c5c1c71e.zip');
INSERT INTO `op_cloud_template`
VALUES ('21', '51劳动节-2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/a65509e5cee18c6dda134cde50009892.png\"]',
        '51劳动节-2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/db17654eddbc57754dfb9542f57ffd6a.zip');
INSERT INTO `op_cloud_template`
VALUES ('22', '618年中盛典',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/3dec44461189a8c99b157e426ec98aa4.png\"]',
        '618年中盛典', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/c651005b5944a0b61871b82459edece4.zip');
INSERT INTO `op_cloud_template`
VALUES ('23', '618年中盛典-2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/35120f94cf467eb8874aa67afe0b12c4.png\"]',
        '618年中盛典-2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/fc0274194a2297cceee2d62bdb7ec248.zip');
INSERT INTO `op_cloud_template`
VALUES ('24', '端午节2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/995334b8ad426121aa8565297b51fcca.png\"]',
        '端午节2', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/0605dac1b23afa05e59ee253c5785ff2.zip');
INSERT INTO `op_cloud_template`
VALUES ('25', '端午节1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/da8a5f4aa964f4dd933b60f9643a7353.png\"]',
        '端午节1', '0.00', 'diy', '0.0.1',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/61173884b5b6f8bb604ad0bfe5998b22.zip');
INSERT INTO `op_cloud_template`
VALUES ('26', '七夕节',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/7c028875992161e54de2ca8ff3368d07.png\"]',
        '七夕节', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/d6b97eba29e23e34fc6540018a745a3f.zip');
INSERT INTO `op_cloud_template`
VALUES ('27', '国庆节1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/01a2c2b646e906a4030890f63e90b3e5.png\"]',
        '国庆节1', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/46b7d293c7e09914096ab0452405057b.zip');
INSERT INTO `op_cloud_template`
VALUES ('28', '国庆节2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/5d7e89956a98affd664148d8bea71551.png\"]',
        '国庆节2', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2c707440327f70b161354716a39a1323.zip');
INSERT INTO `op_cloud_template`
VALUES ('29', '中秋节1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4f03a7e64fd2d253f5c0377758a3339f.png\"]',
        '中秋节1', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/2236ba6e328a1697f3aad986e7aa87f9.zip');
INSERT INTO `op_cloud_template`
VALUES ('30', '中秋节2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/6a9368b86fda2ae8036eca737a63c889.png\"]',
        '中秋节2', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/6cbfc8b71f419e0744b717acfdf9154e.zip');
INSERT INTO `op_cloud_template`
VALUES ('31', '双十二1',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/d580c61a686ced24053ad0aec7457096.png\"]',
        '双十二1', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/b7855271cd534e42504dc850ff5ff216.zip');
INSERT INTO `op_cloud_template`
VALUES ('32', '双十二2',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/096eb7e2b1c7ace2e8d40580e230ab81.png\"]',
        '双十二2', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/eec38ebdb62248f1476dfb2595ab2883.zip');
INSERT INTO `op_cloud_template`
VALUES ('33', '圣诞节',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/9d5be7805c6895bcb76f91b309bf60c0.png\"]',
        '圣诞节', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/467383db158a2c65edbf1163197ff78b.zip');
INSERT INTO `op_cloud_template`
VALUES ('34', '元旦节',
        '[\"https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4d7d33a1854ecd602ef737d9f465f048.png\"]',
        '元旦节', '0.00', 'diy', '0.01',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/f04d03473489c20f894655e76d2a6b76.zip');

-- ----------------------------
-- Table structure for op_community_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_community_activity`;
CREATE TABLE `op_community_activity`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `status`        int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',
    `is_delete`     tinyint(4) NOT NULL DEFAULT '0',
    `created_at`    timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    timestamp    NOT NULL,
    `deleted_at`    timestamp    NOT NULL,
    `title`         varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
    `start_at`      timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',
    `end_at`        timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',
    `is_area_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独区域购买',
    `area_limit`    longtext     NOT NULL,
    `full_price`    varchar(200) NOT NULL DEFAULT '' COMMENT '满减方案json',
    `condition`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭，1开启人数条件，2开启件数条件',
    `num`           int(11) NOT NULL DEFAULT '0' COMMENT '条件数量',
    PRIMARY KEY (`id`) USING BTREE,
    KEY             `idx_1` (`mall_id`,`is_delete`,`created_at`),
    KEY             `sort` (`start_at`,`end_at`),
    KEY             `mall_id` (`mall_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='社区团购活动';

-- ----------------------------
-- Table structure for op_community_activity_locking
-- ----------------------------
DROP TABLE IF EXISTS `op_community_activity_locking`;
CREATE TABLE `op_community_activity_locking`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `activity_id`  int(11) NOT NULL DEFAULT '0',
    `middleman_id` int(11) NOT NULL DEFAULT '0',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_activity_robots
-- ----------------------------
DROP TABLE IF EXISTS `op_community_activity_robots`;
CREATE TABLE `op_community_activity_robots`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `activity_id`  int(11) NOT NULL DEFAULT '0',
    `middleman_id` int(11) NOT NULL DEFAULT '0',
    `robots_ids`   varchar(100) NOT NULL DEFAULT '',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `activity_id` (`activity_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_address
-- ----------------------------
DROP TABLE IF EXISTS `op_community_address`;
CREATE TABLE `op_community_address`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `user_id`     int(11) NOT NULL,
    `name`        varchar(255)  NOT NULL COMMENT '收货人',
    `province_id` int(11) NOT NULL,
    `province`    varchar(255)  NOT NULL COMMENT '省份名称',
    `city_id`     int(11) NOT NULL,
    `city`        varchar(255)  NOT NULL COMMENT '城市名称',
    `district_id` int(11) NOT NULL,
    `district`    varchar(255)  NOT NULL COMMENT '县区名称',
    `mobile`      varchar(255)  NOT NULL COMMENT '联系电话',
    `detail`      varchar(1000) NOT NULL COMMENT '详细地址',
    `is_default`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp     NOT NULL,
    `deleted_at`  timestamp     NOT NULL,
    `latitude`    varchar(255)  NOT NULL DEFAULT '' COMMENT '经度',
    `longitude`   varchar(255)  NOT NULL DEFAULT '' COMMENT '纬度',
    `location`    varchar(255)  NOT NULL DEFAULT '' COMMENT '位置',
    PRIMARY KEY (`id`),
    KEY           `user_id` (`user_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='团长地址';

-- ----------------------------
-- Table structure for op_community_bonus_log
-- ----------------------------
DROP TABLE IF EXISTS `op_community_bonus_log`;
CREATE TABLE `op_community_bonus_log`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL DEFAULT '0',
    `user_id`      int(11) NOT NULL DEFAULT '0',
    `order_id`     int(11) NOT NULL DEFAULT '0',
    `activity_id`  int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
    `desc`         varchar(200)   NOT NULL DEFAULT '',
    `price`        decimal(10, 2) NOT NULL DEFAULT '0.00',
    `profit_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '利润',
    `created_at`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_cart
-- ----------------------------
DROP TABLE IF EXISTS `op_community_cart`;
CREATE TABLE `op_community_cart`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL DEFAULT '0',
    `mch_id`             int(11) NOT NULL DEFAULT '0',
    `user_id`            int(11) NOT NULL DEFAULT '0',
    `activity_id`        int(11) NOT NULL DEFAULT '0',
    `community_goods_id` int(11) NOT NULL DEFAULT '0',
    `goods_id`           int(11) NOT NULL DEFAULT '0',
    `goods_attr_id`      int(11) NOT NULL DEFAULT '0',
    `attr_info`          longtext  NOT NULL,
    `num`                int(11) NOT NULL DEFAULT '0',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `created_at`         timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp NOT NULL,
    `deleted_at`         timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `user_id` (`user_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购用户购物车';

-- ----------------------------
-- Table structure for op_community_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_community_goods`;
CREATE TABLE `op_community_goods`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `goods_id`    int(11) NOT NULL DEFAULT '0',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp NOT NULL,
    `deleted_at`  timestamp NOT NULL,
    `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
    `sort`        int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `activity` (`activity_id`) USING BTREE,
    KEY           `goods_id` (`goods_id`) USING BTREE,
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='社区团购商品';

-- ----------------------------
-- Table structure for op_community_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_community_goods_attr`;
CREATE TABLE `op_community_goods_attr`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `goods_id`     int(11) NOT NULL DEFAULT '0',
    `attr_id`      int(11) NOT NULL DEFAULT '0',
    `supply_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '供货价',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `goods_id` (`goods_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_log
-- ----------------------------
DROP TABLE IF EXISTS `op_community_log`;
CREATE TABLE `op_community_log`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `user_id`      int(11) NOT NULL DEFAULT '0',
    `middleman_id` int(11) NOT NULL DEFAULT '0',
    `activity_id`  int(11) NOT NULL DEFAULT '0',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `created_at`   timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`   timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`   timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY            `user_id` (`user_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_middleman
-- ----------------------------
DROP TABLE IF EXISTS `op_community_middleman`;
CREATE TABLE `op_community_middleman`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `user_id`           int(11) NOT NULL,
    `money`             decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '可提现利润',
    `total_money`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '累计利润',
    `status`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '0--申请中 1--通过 2--拒绝 -1--未支付',
    `apply_at`          timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',
    `become_at`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '通过审核时间',
    `delete_first_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除后是否显示0--不显示 1--显示',
    `reason`            varchar(255)   NOT NULL DEFAULT '' COMMENT '审核结果原因',
    `content`           varchar(255)   NOT NULL DEFAULT '' COMMENT '备注',
    `name`              varchar(255)   NOT NULL DEFAULT '' COMMENT '收货人',
    `mobile`            varchar(255)   NOT NULL DEFAULT '' COMMENT '联系电话',
    `is_delete`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`        timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp      NOT NULL,
    `deleted_at`        timestamp      NOT NULL,
    `pay_price`         decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '支付的金额',
    `token`             varchar(255)   NOT NULL DEFAULT '',
    `pay_type`          tinyint(255) NOT NULL DEFAULT '0' COMMENT '支付方式',
    `pay_time`          timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
    `total_price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '销售总额',
    `order_count`       int(11) NOT NULL DEFAULT '0' COMMENT '订单总数',
    PRIMARY KEY (`id`),
    KEY                 `user_id` (`user_id`) USING BTREE,
    KEY                 `mall_id` (`mall_id`),
    KEY                 `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购 团长信息';

-- ----------------------------
-- Table structure for op_community_middleman_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_community_middleman_activity`;
CREATE TABLE `op_community_middleman_activity`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `middleman_id` int(11) NOT NULL DEFAULT '0' COMMENT '团长user_id',
    `activity_id`  int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
    `is_remind`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒 0--未提醒 1--已提醒',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
    PRIMARY KEY (`id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_order
-- ----------------------------
DROP TABLE IF EXISTS `op_community_order`;
CREATE TABLE `op_community_order`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL DEFAULT '0',
    `order_id`       int(11) NOT NULL DEFAULT '0',
    `activity_id`    int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
    `user_id`        int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `middleman_id`   int(11) NOT NULL COMMENT '团长ID',
    `profit_price`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '总利润',
    `profit_data`    text           NOT NULL COMMENT '利润详情',
    `full_price`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '满多少',
    `discount_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `activity_no`    varchar(100)   NOT NULL DEFAULT '' COMMENT '活动编号',
    `no`             int(11) NOT NULL DEFAULT '0' COMMENT '编号',
    `num`            int(11) NOT NULL DEFAULT '0',
    `created_at`     timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`     timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`     timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_community_relations
-- ----------------------------
DROP TABLE IF EXISTS `op_community_relations`;
CREATE TABLE `op_community_relations`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `user_id`      int(11) NOT NULL DEFAULT '0',
    `middleman_id` int(11) NOT NULL DEFAULT '0',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `user_id` (`user_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='社区团购 用户与团长关系';

-- ----------------------------
-- Table structure for op_community_switch
-- ----------------------------
DROP TABLE IF EXISTS `op_community_switch`;
CREATE TABLE `op_community_switch`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `middleman_id` int(11) NOT NULL DEFAULT '0',
    `activity_id`  int(11) NOT NULL DEFAULT '0',
    `goods_id`     int(11) NOT NULL DEFAULT '0',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `activity_id` (`activity_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='活动商品关闭表';

-- ----------------------------
-- Table structure for op_composition
-- ----------------------------
DROP TABLE IF EXISTS `op_composition`;
CREATE TABLE `op_composition`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT COMMENT ' ',
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255)   NOT NULL DEFAULT '' COMMENT '套餐名',
    `price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '套餐价',
    `type`       tinyint(255) NOT NULL DEFAULT '1' COMMENT '套餐类型 1--固定套餐 2--搭配套餐',
    `status`     int(11) NOT NULL DEFAULT '0' COMMENT '是否上架 0--下架 1--上架',
    `sort`       int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    `is_delete`  int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    `sort_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '排序的优惠金额',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='套餐表';

-- ----------------------------
-- Table structure for op_composition_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_composition_goods`;
CREATE TABLE `op_composition_goods`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `model_id`       int(11) NOT NULL COMMENT '套餐id',
    `goods_id`       int(11) NOT NULL COMMENT '商品id',
    `is_host`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是主商品',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
    `payment_people` int(11) NOT NULL DEFAULT '0' COMMENT '支付人数',
    `payment_num`    int(11) NOT NULL DEFAULT '0' COMMENT '支付件数',
    `payment_amount` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
    `created_at`     timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`) USING BTREE,
    KEY              `model_id` (`model_id`) USING BTREE,
    KEY              `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_composition_order
-- ----------------------------
DROP TABLE IF EXISTS `op_composition_order`;
CREATE TABLE `op_composition_order`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `order_id`       int(11) NOT NULL,
    `composition_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠金额',
    `price`          decimal(10, 2) NOT NULL,
    `is_delete`      tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_action_log
-- ----------------------------
DROP TABLE IF EXISTS `op_core_action_log`;
CREATE TABLE `op_core_action_log`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `user_id`       int(11) NOT NULL COMMENT '操作人ID',
    `model`         varchar(255) CHARACTER SET utf8mb4      NOT NULL DEFAULT '' COMMENT '模型名称',
    `model_id`      int(11) NOT NULL COMMENT '模模型ID',
    `before_update` longtext COLLATE utf8mb4_german2_ci,
    `after_update`  longtext COLLATE utf8mb4_german2_ci,
    `created_at`    timestamp                               NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    `remark`        varchar(255) COLLATE utf8mb4_german2_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY             `store_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;

-- ----------------------------
-- Table structure for op_core_exception_log
-- ----------------------------
DROP TABLE IF EXISTS `op_core_exception_log`;
CREATE TABLE `op_core_exception_log`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `level`      tinyint(4) NOT NULL DEFAULT '1' COMMENT '异常等级1.报错|2.警告|3.记录信息',
    `title`      mediumtext NOT NULL COMMENT '异常标题',
    `content`    mediumtext NOT NULL COMMENT '异常内容',
    `created_at` timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_file
-- ----------------------------
DROP TABLE IF EXISTS `op_core_file`;
CREATE TABLE `op_core_file`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) DEFAULT '0',
    `mch_id`     int(11) DEFAULT '0',
    `file_name`  varchar(255)   DEFAULT '' COMMENT '文件名称',
    `percent`    decimal(11, 2) DEFAULT '0.00' COMMENT '下载进度',
    `status`     tinyint(1) DEFAULT '0' COMMENT '是否完成',
    `is_delete`  tinyint(1) DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `user_id`    int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_plugin
-- ----------------------------
DROP TABLE IF EXISTS `op_core_plugin`;
CREATE TABLE `op_core_plugin`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`         varchar(64) NOT NULL,
    `display_name` varchar(64) NOT NULL,
    `version`      varchar(64) NOT NULL DEFAULT '',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `created_at`   timestamp NULL DEFAULT NULL,
    `updated_at`   timestamp NULL DEFAULT NULL,
    `deleted_at`   timestamp NULL DEFAULT NULL,
    `pic_url`      text,
    `desc`         longtext,
    `sort`         int(11) NOT NULL DEFAULT '100',
    PRIMARY KEY (`id`),
    KEY            `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of op_core_plugin
-- ----------------------------
INSERT INTO `op_core_plugin`
VALUES ('1', 'wxapp', '微信小程序', '1.0.33', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/d52a90d4eb06059d9fdb0641592400b3.png',
        '在微信小程序中经营你的店铺', '1');
INSERT INTO `op_core_plugin`
VALUES ('2', 'diy', 'DIY装修', '1.0.105', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/4903220b146520fd91533ed140d2542f.png',
        'DIY店铺风格和元素，千人千面', '2');
INSERT INTO `op_core_plugin`
VALUES ('3', 'advance', '商品预售', '1.0.57', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/888a3c5dbc7881c74e2ae4299c4e6e2c.png',
        '提前交付定金，尾款享受优惠', '1');
INSERT INTO `op_core_plugin`
VALUES ('4', 'composition', '套餐组合', '1.0.15', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/753525bea4854dae63ce83f575fa3a10.png',
        '套餐组合', '2');
INSERT INTO `op_core_plugin`
VALUES ('5', 'aliapp', '支付宝小程序', '1.0.147', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/e1f631677b48470eeb0c50f811165472.png',
        '在支付宝小程序中经营你的店铺', '2');
INSERT INTO `op_core_plugin`
VALUES ('6', 'app_admin', '手机端管理', '1.0.7', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/e3e79ca9b97dba95ae79b9ac1bed16f9.png',
        '手机端操作管理店铺', '3');
INSERT INTO `op_core_plugin`
VALUES ('7', 'assistant', '采集助手', '1.0.5', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/8064507d4b463ae2be31dd86ccc4bd8d.png',
        '采集助手', '1');
INSERT INTO `op_core_plugin`
VALUES ('8', 'bargain', '砍价', '1.0.63', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/10da8fa3ab90d190fb6c8dcb1fa0ac24.png',
        '邀请好友砍价后低价购买', '5');
INSERT INTO `op_core_plugin`
VALUES ('9', 'bdapp', '百度小程序', '1.0.147', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/c606e51d6c842f68b768c6780f6dbc87.png',
        '在百度小程序中经营你的店铺', '4');
INSERT INTO `op_core_plugin`
VALUES ('10', 'bonus', '团队分红', '1.0.39', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/6831954dc342f2ba066621409fe1e60d.png',
        '队长获得队员订单分红', '2');
INSERT INTO `op_core_plugin`
VALUES ('11', 'booking', '预约', '1.0.71', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/07c662e46ade264338df7544c5f5057f.png',
        '提前线下消费或服务', '4');
INSERT INTO `op_core_plugin`
VALUES ('12', 'check_in', '签到插件', '1.0.26', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/9f8feafc5d1619c3d897e56ba2fd4147.png',
        '促进店铺访问量和用户活跃度', '3');
INSERT INTO `op_core_plugin`
VALUES ('13', 'clerk', '核销员', '1.0.13', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/2743ec86b035db21eaa5e3019e33c714.png',
        '手机端扫码核销，查询订单', '4');
INSERT INTO `op_core_plugin`
VALUES ('14', 'dianqilai', '客服系统', '1.0.7', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/9a1ae4508528799d9ca5543a857b3237.png',
        '促进商家和买家之间的高效交流', '5');
INSERT INTO `op_core_plugin`
VALUES ('15', 'ecard', '电子卡密', '1.0.7', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/ea1a03f8a27561ea18ec1312b102c3e3.png',
        '电子卡密', '10');
INSERT INTO `op_core_plugin`
VALUES ('16', 'fxhb', '裂变拆“红包”', '1.0.33', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/9a7e9bb462ece248a6da22d7a2a39b84.png',
        '裂变式邀请好友拆“红包”', '4');
INSERT INTO `op_core_plugin`
VALUES ('17', 'gift', '社交送礼', '1.0.35', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/e88d4dd935389049bdeda86856b59ed3.png',
        '购买礼品送给朋友', '6');
INSERT INTO `op_core_plugin`
VALUES ('18', 'integral_mall', '积分商城', '1.0.61', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/4677340a313d1d6a417492dd3b615540.png',
        '使用积分或积分+现金兑换商品', '1');
INSERT INTO `op_core_plugin`
VALUES ('19', 'lottery', '幸运抽奖', '1.0.63', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/0bde7f03396f64b1a6a602ffdc384fe8.png',
        '裂变玩法，抽取幸运客户赠送奖品', '5');
INSERT INTO `op_core_plugin`
VALUES ('20', 'mch', '多商户', '1.0.76', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/e518720e0d614439e51b49735fe1f842.png',
        '获取入驻商流量，自营+商户入驻', '6');
INSERT INTO `op_core_plugin`
VALUES ('21', 'miaosha', '整点秒杀', '1.0.62', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/7ec0386824388b8ce18040857058827d.png',
        '引导客户快速抢购', '7');
INSERT INTO `op_core_plugin`
VALUES ('22', 'pick', 'N元任选', '1.0.28', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/a4f3d48897eb696fd936816f33a2da70.png',
        'N元任选', '3');
INSERT INTO `op_core_plugin`
VALUES ('23', 'pintuan', '拼团', '1.0.104', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/1ad43702df97bdea25452f00b3b49f5e.png',
        '引导客户邀请朋友一起拼团购买', '8');
INSERT INTO `op_core_plugin`
VALUES ('24', 'pond', '九宫格', '1.0.44', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/4b9e58ea9aa6f60e08b5aaef20426ac9.png',
        '抽积分、优惠券、实物等', '1');
INSERT INTO `op_core_plugin`
VALUES ('25', 'quick_share', '一键发圈', '1.0.15', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/7e2836a9861b31de1eb548f067bb2fde.png',
        '一键保存文案和图片，高效发朋友圈', '7');
INSERT INTO `op_core_plugin`
VALUES ('26', 'scan_code_pay', '当面付', '1.0.34', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/0680c8f2ef2632d373750bd8addcfe8b.png',
        '线下场景扫码当面支付', '6');
INSERT INTO `op_core_plugin`
VALUES ('27', 'scratch', '刮刮卡', '1.0.42', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/28fd30646970fe742350d47c3494aee4.png',
        '刮开卡片参与抽奖', '8');
-- INSERT INTO `op_core_plugin` VALUES ('28', 'shopping', '好物圈', '1.0.9', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
--         'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/994df3a6c8dc456d4a3a3614b4deaec4.png',
--         '向微信好友推荐好商品', '9');
INSERT INTO `op_core_plugin`
VALUES ('29', 'step', '步数宝', '1.0.69', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/7e2e64e891d444d5c31824fee9f8fb88.png',
        '步数兑换商品', '9');
INSERT INTO `op_core_plugin`
VALUES ('30', 'stock', '股东分红', '1.0.20', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/8174c99e859fd446abd47b877fd41a45.png',
        '股东分红', '10');
INSERT INTO `op_core_plugin`
VALUES ('31', 'ttapp', '抖音/头条小程序', '1.0.147', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/0192ac346351c5fbdc2ea1c953d097f2.png',
        '在抖音/头条小程序中经营你的店铺', '3');
INSERT INTO `op_core_plugin`
VALUES ('32', 'vip_card', '超级会员卡', '1.0.51', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/9c719748ee725092f09fdc5ee18538f2.png',
        '享受超级会员折扣和福利', '2');
INSERT INTO `op_core_plugin`
VALUES ('33', 'region', '区域代理', '1.0.9', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/1a698f7c1b6c64b57f878d735734858b.png',
        '区域代理', '11');
INSERT INTO `op_core_plugin`
VALUES ('34', 'flash_sale', '限时抢购', '1.0.12', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/0c4882580c64ac1c5a239b653b2e429f.png',
        '限时抢购', '11');
INSERT INTO `op_core_plugin`
VALUES ('35', 'community', '社区团购', '1.0.23', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/8fec059e37dbf85adb01bd1d7f904d1e.png',
        '团长群内推广，本地社区自提', '10');
INSERT INTO `op_core_plugin`
VALUES ('36', 'exchange', '兑换中心', '1.0.14', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/e7116ea1eee1ae77747cca91cc9f7fed.png',
        '提货卡、礼品卡、送礼神器', '10');
INSERT INTO `op_core_plugin`
VALUES ('37', 'wholesale', '商品批发', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/a646bebae632d967be78ee275e9921a5.png',
        '商品批发', '100');
INSERT INTO `op_core_plugin`
VALUES ('38', 'wechat', '公众号商城', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/75f92915101acd808800a72d022b0940.png',
        '公众号商城', '100');
INSERT INTO `op_core_plugin`
VALUES ('39', 'mobile', 'H5商城', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/68ac2d12965cb0bed7c2c5d357418826.png',
        'H5商城', '100');
INSERT INTO `op_core_plugin`
VALUES ('40', 'teller', '收银台', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/plugins/775debae7be373dbb37d2c9d3ca269ad.png',
        '门店收银与线上商城完美结合', '100');
INSERT INTO `op_core_plugin`
VALUES ('41', 'invoice', '发票管理', '1.0.0', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        '', '发票管理', '100');
INSERT INTO `op_core_plugin`
VALUES ('42', 'app', 'APP商城', '1.0.0', '0', '0000-00-00 00:00:00', '2021-11-23 14:03:09', '0000-00-00 00:00:00',
        '', 'APP商城', '100');
INSERT INTO `op_core_plugin`
VALUES ('43', 'minishop', '交易组件', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-30 16:41:50', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4-5-14/plugins/e6ac30ca874a0ac27668c201a43b5ac5.png',
        '对接微信视频号，实现从微信视频号主页、视频号直播，直接跳转商家制作的小程序商城。', '100');
INSERT INTO `op_core_plugin`
VALUES ('44', 'scrm', '企业微信SCRM', '1.0.3', '0', '0000-00-00 00:00:00', '2021-11-30 16:41:50', '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4-5-14/plugins/4b7e0648144a1bf055ef8d12ef241126.png',
        '企业微信SCRM', '100');
INSERT INTO `op_core_plugin`
VALUES ('45', 'url_scheme', '微信链接生成工具', '1.0.1', '0', '0000-00-00 00:00:00', '2021-11-30 16:41:50',
        '0000-00-00 00:00:00',
        'https://mall-template-1251017581.cos.ap-guangzhou.myqcloud.com/4-5-14/plugins/2ee655a1f7f705776d59354bdae7e461.png',
        '微信链接生成工具', '100');
INSERT INTO `op_core_plugin`
VALUES ('46', 'erp', '聚水潭erp', '1.0.0', '0', '0000-00-00 00:00:00', '2021-11-30 16:41:50', '0000-00-00 00:00:00',
        '', '聚水潭erp', '100');

-- ----------------------------
-- Table structure for op_core_queue
-- ----------------------------
DROP TABLE IF EXISTS `op_core_queue`;
CREATE TABLE `op_core_queue`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `channel`     varchar(64) NOT NULL,
    `job`         blob        NOT NULL,
    `pushed_at`   int(11) NOT NULL,
    `ttr`         int(11) NOT NULL,
    `delay`       int(11) NOT NULL DEFAULT '0',
    `priority`    int(11) unsigned NOT NULL DEFAULT '1024',
    `reserved_at` int(11) DEFAULT NULL,
    `attempt`     int(11) DEFAULT NULL,
    `done_at`     int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY           `channel` (`channel`),
    KEY           `reserved_at` (`reserved_at`),
    KEY           `priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_queue_data
-- ----------------------------
DROP TABLE IF EXISTS `op_core_queue_data`;
CREATE TABLE `op_core_queue_data`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `queue_id` int(11) NOT NULL DEFAULT '0' COMMENT '队列返回值',
    `token`    varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    KEY        `queue_id` (`queue_id`),
    KEY        `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='队列存储';

-- ----------------------------
-- Table structure for op_core_session
-- ----------------------------
DROP TABLE IF EXISTS `op_core_session`;
CREATE TABLE `op_core_session`
(
    `id`     char(40) NOT NULL,
    `expire` int(11) DEFAULT NULL,
    `DATA`   blob,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_task
-- ----------------------------
DROP TABLE IF EXISTS `op_core_task`;
CREATE TABLE `op_core_task`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `token`         varchar(64) CHARACTER SET utf8  NOT NULL,
    `delay_seconds` int(11) NOT NULL,
    `is_executed`   int(1) NOT NULL,
    `class`         varchar(128) CHARACTER SET utf8 NOT NULL,
    `params`        longtext,
    `content`       longtext,
    `is_delete`     int(1) NOT NULL,
    `created_at`    timestamp NULL DEFAULT NULL,
    `updated_at`    timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_template
-- ----------------------------
DROP TABLE IF EXISTS `op_core_template`;
CREATE TABLE `op_core_template`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '模板id',
    `name`        varchar(255)   NOT NULL DEFAULT '' COMMENT '模板名称',
    `author`      varchar(255)   NOT NULL DEFAULT '' COMMENT '作者',
    `price`       decimal(10, 0) NOT NULL DEFAULT '0' COMMENT '价格',
    `pics`        longtext       NOT NULL,
    `data`        longtext       NOT NULL COMMENT '数据',
    `order_no`    varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `version`     varchar(255)   NOT NULL DEFAULT '' COMMENT '版本号',
    `type`        varchar(255)   NOT NULL DEFAULT '' COMMENT 'home--首页布局 diy--DIY模板',
    `detail`      longtext       NOT NULL,
    `is_delete`   tinyint(1) NOT NULL,
    `created_at`  timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp      NOT NULL,
    `deleted_at`  timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY           `template_id` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_template_edit
-- ----------------------------
DROP TABLE IF EXISTS `op_core_template_edit`;
CREATE TABLE `op_core_template_edit`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '模板id',
    `name`        varchar(255)   NOT NULL DEFAULT '' COMMENT '修改后名称',
    `price`       decimal(10, 0) NOT NULL DEFAULT '0' COMMENT '修改后价格',
    PRIMARY KEY (`id`),
    KEY           `template_id` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_template_type
-- ----------------------------
DROP TABLE IF EXISTS `op_core_template_type`;
CREATE TABLE `op_core_template_type`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `template_id` int(11) NOT NULL,
    `type`        varchar(255) NOT NULL DEFAULT '' COMMENT '模板适用地方',
    `is_delete`   tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY           `template_id` (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='模板市场中模板适用的地方';

-- ----------------------------
-- Table structure for op_core_validate_code
-- ----------------------------
DROP TABLE IF EXISTS `op_core_validate_code`;
CREATE TABLE `op_core_validate_code`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `target`       varchar(255) NOT NULL,
    `code`         varchar(128) NOT NULL,
    `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp    NOT NULL,
    `is_validated` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已验证：0=未验证，1-已验证',
    PRIMARY KEY (`id`),
    KEY            `target` (`target`),
    KEY            `code` (`code`),
    KEY            `created_at` (`created_at`),
    KEY            `updated_at` (`updated_at`),
    KEY            `is_validated` (`is_validated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信、邮箱验证码';

-- ----------------------------
-- Table structure for op_core_validate_code_log
-- ----------------------------
DROP TABLE IF EXISTS `op_core_validate_code_log`;
CREATE TABLE `op_core_validate_code_log`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `target`     varchar(255) NOT NULL DEFAULT '',
    `content`    varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon`;
CREATE TABLE `op_coupon`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `mch_id`            int(11) NOT NULL DEFAULT '0' COMMENT '多商户id',
    `name`              varchar(255)   NOT NULL COMMENT '优惠券名称',
    `type`              smallint(1) NOT NULL COMMENT '优惠券类型：1=折扣，2=满减',
    `discount`          decimal(3, 1)  NOT NULL DEFAULT '10.0' COMMENT '折扣率',
--     `pic_url`           varchar(200)   NOT NULL DEFAULT '' COMMENT '未用',
--     `desc`              varchar(2000)  NOT NULL DEFAULT '' COMMENT '未用',
    `min_price`         decimal(10, 2) NOT NULL COMMENT '最低消费金额',
    `sub_price`         decimal(10, 2) NOT NULL COMMENT '优惠金额',
    `total_count`       int(11) NOT NULL DEFAULT '-1' COMMENT '可发放的数量（剩余数量）',
    `sort`              int(11) NOT NULL DEFAULT '1' COMMENT '排序按升序排列',
    `expire_type`       smallint(1) NOT NULL COMMENT '到期类型：1=领取后N天过期，2=指定有效期',
    `expire_day`        int(11) NOT NULL DEFAULT '0' COMMENT '有效天数，expire_type=1时',
    `begin_time`        timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期开始时间',
    `end_time`          timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期结束时间',
    `appoint_type`      smallint(11) NOT NULL COMMENT '1 指定分类 2 指定商品 3全部',
    `rule`              varchar(2000)  NOT NULL DEFAULT '' COMMENT '使用说明',
    `is_member`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否指定会员等级',
    `is_delete`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at`        timestamp      NOT NULL,
    `created_at`        timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp      NOT NULL,
    `discount_limit`    decimal(10, 2)          DEFAULT NULL COMMENT '折扣优惠上限',
    `can_receive_count` int(11) NOT NULL DEFAULT '1' COMMENT '可领取数量',
    `app_share_title`   varchar(255)   NOT NULL DEFAULT '',
    `app_share_pic`     varchar(255)   NOT NULL DEFAULT '',
    `use_obtain`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取后赠送',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                 `store_id` (`mall_id`) USING BTREE,
    KEY                 `mch_id` (`mch_id`) USING BTREE,
    KEY                 `is_delete` (`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_auto_send
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_auto_send`;
CREATE TABLE `op_coupon_auto_send`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `coupon_id`  int(11) NOT NULL COMMENT '优惠卷',
    `event`      int(11) NOT NULL DEFAULT '1' COMMENT '触发事件：1=分享，2=购买并付款',
    `send_count` int(11) NOT NULL DEFAULT '0' COMMENT '最多发放次数，0表示不限制',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at` timestamp NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `type`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '领取人 0--所有人 1--指定用户',
    `user_list`  longtext COMMENT '指定用户id列表',
    PRIMARY KEY (`id`),
    KEY          `coupon_id` (`coupon_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_cat_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_cat_relation`;
CREATE TABLE `op_coupon_cat_relation`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `coupon_id` int(11) NOT NULL COMMENT '优惠券',
    `cat_id`    int(11) NOT NULL COMMENT '分类',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY         `coupon_id` (`coupon_id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_center
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_center`;
CREATE TABLE `op_coupon_center`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0' COMMENT '多商户id',
    `coupon_id`  int(11) NOT NULL COMMENT '优惠券id',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `coupon_id` (`coupon_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_goods_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_goods_relation`;
CREATE TABLE `op_coupon_goods_relation`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `coupon_id`          int(11) NOT NULL COMMENT '优惠券',
    `goods_warehouse_id` int(11) NOT NULL COMMENT '商品',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY                  `coupon_id` (`coupon_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_mall_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_mall_relation`;
CREATE TABLE `op_coupon_mall_relation`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `order_id`       int(11) NOT NULL COMMENT '订单id',
    `type`           varchar(20) NOT NULL COMMENT ' use优惠券自动发放',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `user_coupon_id` (`user_coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_coupon_member_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_coupon_member_relation`;
CREATE TABLE `op_coupon_member_relation`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `coupon_id`    int(11) NOT NULL COMMENT '优惠券id',
    `member_level` int(11) NOT NULL COMMENT '会员id',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`   timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`   timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY            `coupon_id` (`coupon_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_delivery
-- ----------------------------
DROP TABLE IF EXISTS `op_delivery`;
CREATE TABLE `op_delivery`
(
    `id`                  int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`             int(11) NOT NULL,
    `mch_id`              int(11) NOT NULL DEFAULT '0',
    `express_id`          int(11) NOT NULL DEFAULT '0' COMMENT '快递公司id',
    `customer_account`    varchar(255) NOT NULL DEFAULT '' COMMENT '电子面单客户账号',
    `customer_pwd`        varchar(255) NOT NULL DEFAULT '' COMMENT '电子面单密码',
    `month_code`          varchar(255) NOT NULL DEFAULT '' COMMENT '月结编码',
    `outlets_name`        varchar(255) NOT NULL DEFAULT '' COMMENT '网点名称',
    `outlets_code`        varchar(255) NOT NULL DEFAULT '' COMMENT '网点编码',
    `company`             varchar(255) NOT NULL DEFAULT '' COMMENT '发件人公司',
    `name`                varchar(255) NOT NULL COMMENT '发件人名称',
    `tel`                 varchar(255) NOT NULL DEFAULT '' COMMENT '发件人电话',
    `mobile`              varchar(255) NOT NULL DEFAULT '' COMMENT '发件人手机',
    `zip_code`            varchar(255) NOT NULL DEFAULT '' COMMENT '发件人邮政编码',
    `province`            varchar(255) NOT NULL COMMENT '发件人省',
    `city`                varchar(255) NOT NULL COMMENT '发件人市',
    `district`            varchar(255) NOT NULL COMMENT '发件人区',
    `address`             varchar(255) NOT NULL COMMENT '发件人详细地址',
    `is_sms`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否订阅短信',
    `template_size`       varchar(255) NOT NULL DEFAULT '' COMMENT '快递鸟电子面单模板规格',
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`          timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          timestamp    NOT NULL,
    `deleted_at`          timestamp    NOT NULL,
    `goods_alias`         varchar(255) NOT NULL DEFAULT '商品' COMMENT '自定义商品别名',
    `is_goods_alias`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '自定义商品别名开关',
    `business_type`       varchar(255) NOT NULL DEFAULT '1' COMMENT '业务类型',
    `is_goods`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否商品信息',
    `kd100_business_type` varchar(255) NOT NULL DEFAULT '' COMMENT '快递100 业务类型',
    `kd100_template`      varchar(255) NOT NULL DEFAULT '' COMMENT '快递100 模板',
    `kd100_t_height`      int(11) NOT NULL DEFAULT '150' COMMENT '打印纸高度',
    `kd100_t_width`       int(11) NOT NULL DEFAULT '100' COMMENT '打印纸宽度',
    PRIMARY KEY (`id`),
    KEY                   `mall_id` (`mall_id`),
    KEY                   `mch_id` (`mch_id`),
    KEY                   `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_ad_coupon
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_ad_coupon`;
CREATE TABLE `op_diy_ad_coupon`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL,
    `is_delete`      tinyint(2) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`     timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_ad_log
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_ad_log`;
CREATE TABLE `op_diy_ad_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `template_id` int(11) NOT NULL,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `raffled_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `user_id` (`user_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_alone_page
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_alone_page`;
CREATE TABLE `op_diy_alone_page`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `type`       varchar(255) DEFAULT '' COMMENT '类型 auth--授权页面',
    `params`     longtext COMMENT '参数',
    `is_delete`  smallint(1) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_open`    smallint(1) DEFAULT '0' COMMENT '是否显示 0--不显示 1--显示',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_coupon_log
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_coupon_log`;
CREATE TABLE `op_diy_coupon_log`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `template_id`    int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL,
    `is_delete`      tinyint(2) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`     timestamp NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY              `mall_id` (`mall_id`),
    KEY              `user_coupon_id` (`user_coupon_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_diy_form
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_form`;
CREATE TABLE `op_diy_form`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `form_data`  longtext NOT NULL,
    `created_at` datetime NOT NULL,
    `is_delete`  tinyint(1) NOT NULL,
    `updated_at` datetime NOT NULL,
    `deleted_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`) USING BTREE,
    KEY          `mall_id` (`mall_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='diy表单信息';

-- ----------------------------
-- Table structure for op_diy_page
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_page`;
CREATE TABLE `op_diy_page`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `title`        varchar(255) NOT NULL,
    `show_navs`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示导航条：0=不显示，1=显示',
    `is_disable`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁用状态：0=启用，1=禁用',
    `is_home_page` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是首页0--否 1--是',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `created_at`   timestamp NULL DEFAULT NULL,
    `updated_at`   timestamp NULL DEFAULT NULL,
    `deleted_at`   timestamp NULL DEFAULT NULL,
    `platform`     varchar(255) NOT NULL DEFAULT '',
    `access_limit` longtext,
    PRIMARY KEY (`id`),
    KEY            `is_delete` (`is_delete`),
    KEY            `mall_id` (`mall_id`),
    KEY            `is_home_page` (`is_home_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_page_nav
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_page_nav`;
CREATE TABLE `op_diy_page_nav`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `page_id`     int(11) NOT NULL,
    `name`        varchar(255) NOT NULL,
    `template_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_diy_template
-- ----------------------------
DROP TABLE IF EXISTS `op_diy_template`;
CREATE TABLE `op_diy_template`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) NOT NULL,
    `data`       longtext     NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `type`       varchar(100) NOT NULL DEFAULT '' COMMENT 'page:微页面',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_ecard
-- ----------------------------
DROP TABLE IF EXISTS `op_ecard`;
CREATE TABLE `op_ecard`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `name`        varchar(255) NOT NULL DEFAULT '' COMMENT '卡密名称',
    `content`     longtext COMMENT '使用说明',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp    NOT NULL,
    `deleted_at`  timestamp    NOT NULL,
    `list`        longtext     NOT NULL COMMENT '卡密字段',
    `sales`       int(11) NOT NULL DEFAULT '0' COMMENT '已售',
    `stock`       int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `is_unique`   tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否去重 0--否 1--是',
    `pre_stock`   int(11) NOT NULL DEFAULT '0' COMMENT '预占用的库存',
    `total_stock` int(11) NOT NULL DEFAULT '0' COMMENT '总库存',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='电子卡密';

-- ----------------------------
-- Table structure for op_ecard_data
-- ----------------------------
DROP TABLE IF EXISTS `op_ecard_data`;
CREATE TABLE `op_ecard_data`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `ecard_id`  int(11) NOT NULL,
    `token`     varchar(255) NOT NULL,
    `key`       varchar(255) NOT NULL,
    `value`     longtext     NOT NULL,
    `is_delete` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    KEY         `ecard_id` (`ecard_id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_ecard_log
-- ----------------------------
DROP TABLE IF EXISTS `op_ecard_log`;
CREATE TABLE `op_ecard_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `ecard_id`   int(11) NOT NULL DEFAULT '0',
    `status`     varchar(255) NOT NULL DEFAULT '' COMMENT '日志操作 add--添加 occupy--占用 sales--卖出 delete--删除',
    `sign`       varchar(255) NOT NULL DEFAULT '' COMMENT '插件标示',
    `number`     int(11) NOT NULL DEFAULT '0' COMMENT '数量',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `goods_id`   int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
    PRIMARY KEY (`id`),
    KEY          `ecard_id` (`ecard_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_ecard_options
-- ----------------------------
DROP TABLE IF EXISTS `op_ecard_options`;
CREATE TABLE `op_ecard_options`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `token`      varchar(150) NOT NULL DEFAULT '' COMMENT '加密字符串',
    `ecard_id`   int(11) NOT NULL DEFAULT '0' COMMENT '电子卡密id',
    `value`      longtext     NOT NULL COMMENT '卡密字段值',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
    `is_sales`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否出售',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_occupy`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被占用 0--否 1--是',
    PRIMARY KEY (`id`),
    KEY          `e_card_id` (`ecard_id`),
    KEY          `token` (`token`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='电子卡密数据';

-- ----------------------------
-- Table structure for op_ecard_order
-- ----------------------------
DROP TABLE IF EXISTS `op_ecard_order`;
CREATE TABLE `op_ecard_order`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `ecard_id`         int(11) NOT NULL,
    `value`            longtext     NOT NULL,
    `order_id`         int(11) NOT NULL,
    `order_detail_id`  int(11) NOT NULL,
    `is_delete`        tinyint(1) NOT NULL,
    `token`            varchar(255) NOT NULL DEFAULT '' COMMENT '加密字符串',
    `ecard_options_id` int(11) NOT NULL,
    `user_id`          int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
    `order_token`      varchar(255) NOT NULL DEFAULT '' COMMENT '订单token',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `order_id` (`order_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='卡密订单列表';

-- ----------------------------
-- Table structure for op_exchange_code
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_code`;
CREATE TABLE `op_exchange_code`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `library_id`       int(11) NOT NULL,
    `type`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 后台 1礼品卡',
    `code`             varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `status`           tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态开关 0禁用 1 启用 2 兑换 3结束',
    `validity_type`    varchar(100)                                           NOT NULL DEFAULT '',
    `valid_end_time`   timestamp                                              NOT NULL DEFAULT '0000-00-00 00:00:00',
    `valid_start_time` timestamp                                              NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_at`       timestamp                                              NOT NULL DEFAULT '0000-00-00 00:00:00',
    `r_user_id`        int(11) NOT NULL DEFAULT '0',
    `r_raffled_at`     timestamp                                              NOT NULL DEFAULT '0000-00-00 00:00:00',
    `r_rewards`        longtext,
    `r_origin`         varchar(100)                                           NOT NULL DEFAULT '' COMMENT '兑换来源',
    `name`             varchar(100)                                           NOT NULL DEFAULT '' COMMENT '后台联系人',
    `mobile`           varchar(100)                                           NOT NULL DEFAULT '' COMMENT '后台手机号码',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                `mall_id` (`mall_id`),
    KEY                `status` (`status`),
    KEY                `library_id` (`library_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_code_log
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_code_log`;
CREATE TABLE `op_exchange_code_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `is_success` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否兑换成功',
    `code`       varchar(255) NOT NULL DEFAULT '',
    `origin`     varchar(100) NOT NULL COMMENT 'admin app',
    `remake`     longtext COMMENT '简单说明',
    `created_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `library_id` int(11) NOT NULL COMMENT '库id',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_coupon_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_coupon_relation`;
CREATE TABLE `op_exchange_coupon_relation`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `code_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL,
    `created_at`     timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY              `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_goods`;
CREATE TABLE `op_exchange_goods`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `library_id` int(11) NOT NULL DEFAULT '0',
    `goods_id`   int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `library_id` (`library_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_library
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_library`;
CREATE TABLE `op_exchange_library`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                int(11) NOT NULL,
    `name`                   varchar(255) NOT NULL COMMENT '名称',
    `remark`                 longtext COMMENT '说明',
    `expire_type`            varchar(100) NOT NULL DEFAULT 'all' COMMENT 'all 永久 fixed 固定 relatively相对',
    `expire_start_time`      timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '固定开始',
    `expire_end_time`        timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '固定开始',
    `expire_start_day`       int(10) NOT NULL DEFAULT '0' COMMENT '相对开始',
    `expire_end_day`         int(10) NOT NULL DEFAULT '0' COMMENT '相对结束',
    `mode`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 全部 1 份',
    `code_format`            varchar(100) NOT NULL DEFAULT 'english_num' COMMENT 'english_num, num',
    `rewards`                longtext     NOT NULL COMMENT '奖励品',
    `rewards_s`              varchar(255) NOT NULL COMMENT '奖励品类型 后台搜索使用',
    `is_recycle`             tinyint(1) NOT NULL COMMENT '是否加入回收站',
    `recycle_at`             timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`              tinyint(1) NOT NULL DEFAULT '0',
    `created_at`             timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`             timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`             timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_limit`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '限制兑换',
    `limit_user_num`         int(8) NOT NULL DEFAULT '0' COMMENT '每位用户每天限制兑换成功次数',
    `limit_user_success_num` int(8) NOT NULL DEFAULT '0' COMMENT '永久兑换成功次数字',
    `limit_user_type`        varchar(8)   NOT NULL DEFAULT 'day' COMMENT '限制兑换类型 day all',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                      `mall_id` (`mall_id`),
    KEY                      `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_order
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_order`;
CREATE TABLE `op_exchange_order`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `order_id`    int(11) NOT NULL,
    `exchange_id` int(11) NOT NULL,
    `code_id`     int(11) NOT NULL,
    `goods_id`    int(11) NOT NULL,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `order_id` (`order_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_record_order
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_record_order`;
CREATE TABLE `op_exchange_record_order`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `order_id`   int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `token`      varchar(255) NOT NULL,
    `code_id`    int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_reward_result
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_reward_result`;
CREATE TABLE `op_exchange_reward_result`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `code_token` varchar(255) NOT NULL DEFAULT '',
    `token`      varchar(32)  NOT NULL DEFAULT '',
    `data`       longtext     NOT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_exchange_svip_order
-- ----------------------------
DROP TABLE IF EXISTS `op_exchange_svip_order`;
CREATE TABLE `op_exchange_svip_order`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `order_id`   int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `code_id`    int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `order_id` (`order_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_favorite
-- ----------------------------
DROP TABLE IF EXISTS `op_favorite`;
CREATE TABLE `op_favorite`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL DEFAULT '0',
    `user_id`      int(11) NOT NULL DEFAULT '0',
    `goods_id`     int(11) NOT NULL DEFAULT '0',
    `mirror_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '收藏时的售价',
    `created_at`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    `is_delete`    int(11) NOT NULL DEFAULT '0',
    `deleted_at`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`   timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY            `user_id` (`user_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_finance
-- ----------------------------
DROP TABLE IF EXISTS `op_finance`;
CREATE TABLE `op_finance`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `user_id`         int(11) NOT NULL,
    `order_no`        varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `price`           decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
    `service_charge`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',
    `type`            varchar(255)   NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
    `extra`           longtext COMMENT '额外信息 例如微信账号、支付宝账号等',
    `status`          int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',
    `is_delete`       int(11) NOT NULL DEFAULT '0',
    `created_at`      datetime       NOT NULL,
    `updated_at`      datetime       NOT NULL,
    `deleted_at`      datetime       NOT NULL,
    `content`         longtext,
    `name`            varchar(255)   NOT NULL DEFAULT '' COMMENT '真实姓名',
    `model`           varchar(255)   NOT NULL DEFAULT '' COMMENT '提现插件(share,bonus,stock,region,mch)',
    `transfer_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0.待转账 | 1.已转账  | 2.拒绝转账',
    `phone`           varchar(255)   NOT NULL DEFAULT '' COMMENT '手机号',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `user_id` (`user_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='提现记录汇总表';

-- ----------------------------
-- Table structure for op_flash_sale_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_flash_sale_activity`;
CREATE TABLE `op_flash_sale_activity`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `status`       int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',
    `is_delete`    tinyint(4) NOT NULL DEFAULT '0',
    `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp    NOT NULL,
    `deleted_at`   timestamp    NOT NULL,
    `title`        varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
    `start_at`     timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',
    `end_at`       timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',
    `notice`       int(11) NOT NULL DEFAULT '0' COMMENT '活动预告',
    `notice_hours` int(11) NOT NULL DEFAULT '0' COMMENT '提前N小时',
    PRIMARY KEY (`id`),
    KEY            `idx_1` (`mall_id`,`is_delete`,`created_at`) USING BTREE,
    KEY            `sort` (`start_at`,`end_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='显示购买活动';

-- ----------------------------
-- Table structure for op_flash_sale_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_flash_sale_goods`;
CREATE TABLE `op_flash_sale_goods`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `status`      tinyint(1) NOT NULL COMMENT '状态 0 关闭 1开启',
    `goods_id`    int(11) NOT NULL DEFAULT '0',
    `type`        tinyint(1) NOT NULL DEFAULT '1' COMMENT '1打折  2减钱  3促销价',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp NOT NULL,
    `deleted_at`  timestamp NOT NULL,
    `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
    `sort`        int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    PRIMARY KEY (`id`),
    KEY           `activity` (`activity_id`) USING BTREE,
    KEY           `goods_id` (`goods_id`) USING BTREE,
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='限时抢购商品表';

-- ----------------------------
-- Table structure for op_flash_sale_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_flash_sale_goods_attr`;
CREATE TABLE `op_flash_sale_goods_attr`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `discount`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '商品折扣',
    `cut`           decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '商品减钱',
    `type`          tinyint(1) NOT NULL DEFAULT '1' COMMENT '1打折  2减钱  3促销价',
    `goods_id`      int(11) NOT NULL,
    `goods_attr_id` int(11) NOT NULL,
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY             `goods_id` (`goods_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='显示购买商品规格';

-- ----------------------------
-- Table structure for op_flash_sale_order_discount
-- ----------------------------
DROP TABLE IF EXISTS `op_flash_sale_order_discount`;
CREATE TABLE `op_flash_sale_order_discount`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `order_id`   int(11) NOT NULL COMMENT '订单id',
    `discount`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `order_id` (`order_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_footprint_data_log
-- ----------------------------
DROP TABLE IF EXISTS `op_footprint_data_log`;
CREATE TABLE `op_footprint_data_log`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `user_id`         int(11) NOT NULL,
    `key`             varchar(60) NOT NULL,
    `value`           varchar(60) NOT NULL,
    `created_at`      timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`      timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`      timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    `statistics_time` timestamp   NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上一次统计的时间',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_footprint_goods_log
-- ----------------------------
DROP TABLE IF EXISTS `op_footprint_goods_log`;
CREATE TABLE `op_footprint_goods_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `user_id`    int(11) NOT NULL DEFAULT '0',
    `goods_id`   int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_form
-- ----------------------------
DROP TABLE IF EXISTS `op_form`;
CREATE TABLE `op_form`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(255) NOT NULL DEFAULT '',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
    `data`       longtext COMMENT '表单内容',
    `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `value`      longtext     NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`),
    KEY          `is_default` (`is_default`),
    KEY          `status` (`status`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_formid
-- ----------------------------
DROP TABLE IF EXISTS `op_formid`;
CREATE TABLE `op_formid`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) NOT NULL,
    `form_id`    varchar(1000) NOT NULL,
    `created_at` timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp     NOT NULL,
    `remains`    int(11) NOT NULL COMMENT '剩余次数',
    `expired_at` timestamp     NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`),
    KEY          `created_at` (`created_at`),
    KEY          `remains` (`remains`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_free_delivery_rules
-- ----------------------------
DROP TABLE IF EXISTS `op_free_delivery_rules`;
CREATE TABLE `op_free_delivery_rules`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(255)   NOT NULL DEFAULT '',
    `type`       tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:订单满额包邮  2:订单满件包邮  3:单商品满额包邮 4:单商品满件包邮',
    `price`      decimal(10, 2) NOT NULL DEFAULT '0.00',
    `detail`     longtext       NOT NULL,
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认  0否 1是',
    `is_delete`  int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_full_reduce_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_full_reduce_activity`;
CREATE TABLE `op_full_reduce_activity`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `name`               varchar(255)  NOT NULL COMMENT '活动标题',
    `content`            varchar(8192) NOT NULL DEFAULT '',
    `status`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',
    `created_at`         timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp     NOT NULL,
    `deleted_at`         timestamp     NOT NULL,
    `start_at`           timestamp     NOT NULL,
    `end_at`             timestamp     NOT NULL,
    `appoint_type`       tinyint(1) NOT NULL COMMENT '1:全部商品\r\n2:全部自营商品\r\n3:指定商品参加\r\n4:指定商品不参加',
    `rule_type`          tinyint(1) NOT NULL COMMENT '1:阶梯满减\r\n2:循环满减',
    `discount_rule`      varchar(512)  NOT NULL DEFAULT '' COMMENT '阶梯满减规则',
    `loop_discount_rule` varchar(128)  NOT NULL DEFAULT '' COMMENT '循环满减规则',
    `appoint_goods`      text          NOT NULL,
    `noappoint_goods`    text          NOT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_fxhb_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_fxhb_activity`;
CREATE TABLE `op_fxhb_activity`
(
    `id`                  int(11) unsigned NOT NULL AUTO_INCREMENT,
    `status`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启活动：0.关闭|1.开启',
    `type`                tinyint(1) NOT NULL DEFAULT '1' COMMENT '红包分配方式：1.随机|2.平均',
    `number`              int(11) NOT NULL COMMENT '拆包人数',
    `count_price`         decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '红包总金额',
    `least_price`         decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
    `effective_time`      int(11) NOT NULL COMMENT '代金券有效期',
    `open_effective_time` int(11) NOT NULL COMMENT '拆红包有效期',
    `coupon_type`         tinyint(1) NOT NULL COMMENT '代金券使用场景：1.指定分类|2.指定商品|3.全场通用',
    `sponsor_num`         int(11) NOT NULL DEFAULT '-1' COMMENT '该用户可发起活动的次数',
    `help_num`            int(11) NOT NULL DEFAULT '-1' COMMENT '帮拆的次数',
    `sponsor_count`       int(11) NOT NULL DEFAULT '-1' COMMENT '此活动可发红包总次数',
    `sponsor_count_type`  tinyint(1) NOT NULL DEFAULT '1' COMMENT '次数扣除方式：0.活动成功扣除|1.活动发起就扣除',
    `start_time`          timestamp      NOT NULL COMMENT '活动开始时间',
    `end_time`            timestamp      NOT NULL COMMENT '活动结束时间',
    `remark`              text           NOT NULL COMMENT '活动规则 ',
    `pic_url`             varchar(255)   NOT NULL DEFAULT '' COMMENT '活动图片',
    `share_title`         text           NOT NULL COMMENT '分享标题',
    `share_pic_url`       varchar(255)   NOT NULL DEFAULT '' COMMENT '分享图片',
    `created_at`          timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          timestamp      NOT NULL,
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at`          timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `mall_id`             int(11) DEFAULT NULL,
    `name`                varchar(255)   NOT NULL COMMENT '活动名称',
    `is_home_model`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页弹窗开关',
    PRIMARY KEY (`id`),
    KEY                   `mall_id` (`mall_id`),
    KEY                   `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_fxhb_activity_cat_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_fxhb_activity_cat_relation`;
CREATE TABLE `op_fxhb_activity_cat_relation`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `activity_id` int(11) NOT NULL COMMENT '活动ID',
    `cat_id`      int(11) NOT NULL COMMENT '分类',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY           `activity_id` (`activity_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_fxhb_activity_goods_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_fxhb_activity_goods_relation`;
CREATE TABLE `op_fxhb_activity_goods_relation`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `activity_id`        int(11) NOT NULL COMMENT '活动ID',
    `goods_warehouse_id` int(11) NOT NULL COMMENT '商品',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY                  `activity_id` (`activity_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_fxhb_user_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_fxhb_user_activity`;
CREATE TABLE `op_fxhb_user_activity`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `user_id`          int(11) NOT NULL,
    `parent_id`        int(11) NOT NULL,
    `fxhb_activity_id` int(11) NOT NULL COMMENT '活动ID',
    `number`           int(11) NOT NULL COMMENT '拆包人数',
    `count_price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '红包总金额',
    `created_at`       timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `is_delete`        tinyint(1) NOT NULL,
    `updated_at`       timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`       timestamp      NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `data`             longtext       NOT NULL COMMENT '活动发起时活动的设置',
    `status`           tinyint(1) NOT NULL COMMENT '状态0--进行中 1--成功 2--失败',
    `mall_id`          int(11) NOT NULL,
    `token`            varchar(255)   NOT NULL,
    `user_coupon_id`   int(11) NOT NULL,
    `get_price`        decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '拆红包获得的金额',
    PRIMARY KEY (`id`),
    KEY                `user_id` (`user_id`),
    KEY                `fxhb_activity_id` (`fxhb_activity_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户参与红包';

-- ----------------------------
-- Table structure for op_gift_log
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_log`;
CREATE TABLE `op_gift_log`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL DEFAULT '0',
    `user_id`          int(11) NOT NULL DEFAULT '0',
    `num`              int(11) NOT NULL DEFAULT '0' COMMENT '礼物总数',
    `created_at`       timestamp                      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`       timestamp                      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`       timestamp                      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    `is_confirm`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '送礼状态：0.未完成送礼|1.已完成送礼',
    `type`             varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '送礼方式',
    `open_time`        timestamp                      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开奖时间',
    `open_num`         int(11) NOT NULL DEFAULT '0' COMMENT '开奖所需人数',
    `open_type`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '0一人拿奖，1多人各领一份奖',
    `bless_word`       varchar(200)                   NOT NULL COMMENT '祝福语',
    `bless_music`      varchar(200) CHARACTER SET utf8         DEFAULT NULL COMMENT '祝福语音',
    `auto_refund_time` timestamp                      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '自动退款时间',
    `is_pay`           tinyint(1) NOT NULL DEFAULT '0',
    `order_id`         int(11) NOT NULL DEFAULT '0',
    `is_cancel`        tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `user_id` (`user_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_gift_lottery
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_lottery`;
CREATE TABLE `op_gift_lottery`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL DEFAULT '0',
    `send_order_id` int(11) NOT NULL DEFAULT '0',
    `user_id`       int(11) NOT NULL DEFAULT '0',
    `created_at`    timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`    timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`    timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    `is_prize`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未中，1中奖',
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`),
    KEY             `user_id` (`user_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_open_result
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_open_result`;
CREATE TABLE `op_gift_open_result`
(
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(32) NOT NULL,
    `data`  longtext,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_order
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_order`;
CREATE TABLE `op_gift_order`
(
    `id`                  int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`             int(11) NOT NULL DEFAULT '0',
    `order_no`            varchar(255) NOT NULL DEFAULT '',
    `goods_id`            int(11) NOT NULL DEFAULT '0',
    `goods_attr_id`       int(11) NOT NULL DEFAULT '0',
    `num`                 int(11) NOT NULL DEFAULT '0',
    `order_id`            int(11) NOT NULL DEFAULT '0' COMMENT '商城订单ID',
    `type`                varchar(60)  NOT NULL COMMENT '送礼方式',
    `created_at`          timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`          timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`          timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0',
    `user_order_id`       int(11) NOT NULL DEFAULT '0',
    `is_refund`           tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款，前端显示超时',
    `buy_order_detail_id` int(11) NOT NULL DEFAULT '0' COMMENT '买礼物的商城订单详情id',
    `gift_id`             int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                   `order_id` (`order_id`),
    KEY                   `order_no` (`order_no`),
    KEY                   `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_order_submit_result
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_order_submit_result`;
CREATE TABLE `op_gift_order_submit_result`
(
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(32) NOT NULL,
    `data`  longtext,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_send_order
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_send_order`;
CREATE TABLE `op_gift_send_order`
(
    `id`                         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                    int(11) NOT NULL DEFAULT '0',
    `mch_id`                     int(11) NOT NULL DEFAULT '0',
    `user_id`                    int(11) NOT NULL DEFAULT '0',
    `gift_id`                    int(11) NOT NULL DEFAULT '0' COMMENT 'gift_log的id',
    `order_no`                   varchar(60)    NOT NULL DEFAULT '',
    `total_price`                decimal(10, 2) NOT NULL COMMENT '订单总金额(含运费)',
    `total_pay_price`            decimal(10, 2) NOT NULL COMMENT '实际支付总费用(含运费）',
    `is_pay`                     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付：0.未支付|1.已支付',
    `pay_type`                   tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付',
    `pay_time`                   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
    `is_refund`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未退款，1已退款',
    `is_confirm`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '送礼状态：0.未完成送礼|1.已完成送礼',
    `created_at`                 timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`                 timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`                 timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`                  tinyint(1) NOT NULL DEFAULT '0',
    `support_pay_types`          text           NOT NULL COMMENT '支持的支付方式，空表示支持系统设置支持的所有方式',
    `token`                      varchar(32)    NOT NULL,
    `total_goods_price`          decimal(10, 2) NOT NULL DEFAULT '0.00',
    `total_goods_original_price` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `member_discount_price`      decimal(10, 2) NOT NULL DEFAULT '0.00',
    `full_reduce_price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '满减活动优惠价格',
    `use_user_coupon_id`         int(11) NOT NULL DEFAULT '0',
    `coupon_discount_price`      decimal(10, 2) NOT NULL DEFAULT '0.00',
    `use_integral_num`           int(11) NOT NULL DEFAULT '0',
    `integral_deduction_price`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `is_cancel`                  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                          `mall_id` (`mall_id`),
    KEY                          `user_id` (`user_id`),
    KEY                          `gift_id` (`gift_id`),
    KEY                          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_send_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_send_order_detail`;
CREATE TABLE `op_gift_send_order_detail`
(
    `id`                    int(11) NOT NULL AUTO_INCREMENT,
    `send_order_id`         int(11) NOT NULL,
    `goods_id`              int(11) NOT NULL,
    `goods_attr_id`         int(11) NOT NULL DEFAULT '0',
    `goods_info`            longtext COMMENT '购买商品信息',
    `num`                   int(11) NOT NULL,
    `unit_price`            decimal(10, 2) NOT NULL COMMENT '商品单价',
    `total_original_price`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '商品原总价(优惠前)',
    `total_price`           decimal(10, 2) NOT NULL COMMENT '商品总价(优惠后)',
    `member_discount_price` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `is_refund`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未退款，1已退款',
    `refund_status`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '售后状态 0--未售后 1--售后中 2--售后结束',
    `created_at`            timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`            timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`            timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`             tinyint(1) NOT NULL DEFAULT '0',
    `receive_num`           int(11) NOT NULL DEFAULT '0' COMMENT '已领取数量',
    `refund_price`          decimal(10, 2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY                     `send_order_id` (`send_order_id`),
    KEY                     `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_setting`;
CREATE TABLE `op_gift_setting`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL DEFAULT '0',
    `title`        varchar(200) NOT NULL,
    `type`         varchar(200) NOT NULL DEFAULT '[]' COMMENT '玩法',
    `auto_refund`  int(11) NOT NULL DEFAULT '0' COMMENT '自动退款天数',
    `auto_remind`  int(11) NOT NULL DEFAULT '0' COMMENT '送礼失败提醒天数',
    `bless_word`   varchar(200) NOT NULL COMMENT '祝福语',
    `ask_gift`     varchar(200) NOT NULL COMMENT '求礼物',
    `is_share`     tinyint(1) NOT NULL DEFAULT '0',
    `is_sms`       tinyint(1) NOT NULL DEFAULT '0',
    `is_mail`      tinyint(1) NOT NULL DEFAULT '0',
    `is_print`     tinyint(1) NOT NULL DEFAULT '0',
    `payment_type` text         NOT NULL,
    `created_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `poster`       longtext     NOT NULL COMMENT '海报',
    `background`   varchar(200) NOT NULL DEFAULT '[]' COMMENT '自定义背景',
    `theme`        text         NOT NULL COMMENT '主题色',
    `send_type`    varchar(200) NOT NULL DEFAULT '[]',
    `explain`      text         NOT NULL COMMENT '规则说明',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_gift_user_order
-- ----------------------------
DROP TABLE IF EXISTS `op_gift_user_order`;
CREATE TABLE `op_gift_user_order`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL DEFAULT '0',
    `user_id`      int(11) NOT NULL DEFAULT '0',
    `gift_id`      int(11) NOT NULL DEFAULT '0',
    `is_turn`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否转赠0未转1已转',
    `turn_no`      varchar(255) NOT NULL DEFAULT '' COMMENT '转赠码',
    `turn_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '被转赠用户ID',
    `is_receive`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领取，1已领取',
    `created_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`   timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `is_win`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未中，1已中',
    `token`        varchar(32)  NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY            `user_id` (`user_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for op_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_goods`;
CREATE TABLE `op_goods`
(
    `id`                           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                      int(11) NOT NULL,
    `mch_id`                       int(11) NOT NULL DEFAULT '0',
    `goods_warehouse_id`           int(11) NOT NULL,
    `status`                       smallint(1) NOT NULL DEFAULT '0' COMMENT '上架状态：0=下架，1=上架',
    `price`                        decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '售价',
    `use_attr`                     smallint(1) NOT NULL DEFAULT '1' COMMENT '是否使用规格：0=不使用，1=使用',
    `attr_groups`                  text CHARACTER SET utf8 NOT NULL COMMENT '商品规格组',
    `goods_stock`                  int(11) NOT NULL DEFAULT '0' COMMENT '商品库存',
    `virtual_sales`                int(11) NOT NULL DEFAULT '0' COMMENT '已出售量',
    `confine_count`                int(11) NOT NULL DEFAULT '-1' COMMENT '购物数量限制',
    `pieces`                       int(11) NOT NULL DEFAULT '0' COMMENT '单品满件包邮',
    `forehead`                     decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '单口满额包邮',
    `shipping_id`                  int(11) NOT NULL DEFAULT '0' COMMENT '包邮模板ID',
    `freight_id`                   int(11) NOT NULL COMMENT '运费模板ID',
    `give_integral`                int(11) NOT NULL DEFAULT '0' COMMENT '赠送积分',
    `give_integral_type`           tinyint(1) NOT NULL DEFAULT '1' COMMENT '赠送积分类型1.固定值 |2.百分比',
    `give_balance`                 decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '赠送余额',
    `give_balance_type`            tinyint(1) NOT NULL DEFAULT '1' COMMENT '赠送余额类型1.固定值 |2.百分比',
    `forehead_integral`            decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '可抵扣积分',
    `forehead_integral_type`       tinyint(1) NOT NULL DEFAULT '1' COMMENT '可抵扣积分类型1.固定值 |2.百分比',
    `accumulative`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许多件累计折扣',
    `individual_share`             smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独分销设置：0=否，1=是',
    `attr_setting_type`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销设置类型 0.普通设置|1.详细设置',
    `is_level`                     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否享受会员价购买',
    `is_level_alone`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独设置会员价',
    `share_type`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '佣金配比 0--固定金额 1--百分比',
    `sign`                         varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商品标示用于区分商品属于什么模块',
    `app_share_pic`                varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '自定义分享图片',
    `app_share_title`              varchar(65)                     NOT NULL DEFAULT '' COMMENT '自定义分享标题',
    `is_default_services`          tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认服务 0.否|1.是',
    `sort`                         int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    `created_at`                   timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                   timestamp                       NOT NULL,
    `deleted_at`                   timestamp                       NOT NULL,
    `is_delete`                    tinyint(1) NOT NULL DEFAULT '0',
    `payment_people`               int(11) NOT NULL DEFAULT '0' COMMENT '支付人数',
    `payment_num`                  int(11) NOT NULL DEFAULT '0' COMMENT '支付件数',
    `payment_amount`               decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '支付金额',
    `payment_order`                int(11) NOT NULL DEFAULT '0' COMMENT '支付订单数',
    `confine_order_count`          int(11) NOT NULL DEFAULT '-1',
    `is_area_limit`                tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独区域购买',
    `area_limit`                   longtext COMMENT '区域限制',
    `form_id`                      int(11) NOT NULL DEFAULT '0' COMMENT '自定义表单id 0--表示默认表单 -1--表示不使用表单',
    `sales`                        int(11) NOT NULL DEFAULT '0' COMMENT '商品实际销量',
    `detail_count`                 int(11) NOT NULL DEFAULT '0' COMMENT '详情浏览量统计',
    `guarantee_title`              varchar(64)                     NOT NULL DEFAULT '' COMMENT '商品服务标题',
    `guarantee_pic`                varchar(2048)                   NOT NULL DEFAULT '' COMMENT '商品服务标识',
    `param_name`                   varchar(255)                    NOT NULL DEFAULT '' COMMENT '参数名称',
    `param_content`                longtext COMMENT '参数内容',
    `limit_buy_status`             smallint(1) NOT NULL DEFAULT '1' COMMENT '限购状态0--关闭 1--开启',
    `limit_buy_type`               varchar(50)                     NOT NULL DEFAULT 'day' COMMENT '限购类型 day--每天 week--每周 month--每月 all--永久',
    `limit_buy_value`              int(11) NOT NULL DEFAULT '-1' COMMENT '限购数量',
    `min_number`                   int(11) NOT NULL DEFAULT '1' COMMENT '起售数量',
    `is_setting_show_and_buy_auth` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独设置浏览和购买权限',
    `show_goods_auth`              varchar(255)                    NOT NULL DEFAULT '-1' COMMENT '会员等级浏览权限',
    `buy_goods_auth`               varchar(255)                    NOT NULL DEFAULT '-1' COMMENT '会员等级购买权限',
    `is_setting_send_type`         smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独设置发货方式0--否 1--是',
    `send_type`                    varchar(255)                    NOT NULL DEFAULT '' COMMENT '发货方式',
    `is_time`                      smallint(1) NOT NULL DEFAULT '0' COMMENT '是否单独设置销售时间',
    `sell_begin_time`              timestamp                       NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开售时间',
    `sell_end_time`                timestamp                       NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
    `video_channel`                text                                     DEFAULT null COMMENT '视频号设置',
    PRIMARY KEY (`id`),
    KEY                            `mall_id` (`mall_id`),
    KEY                            `goods_warehouse_id` (`goods_warehouse_id`),
    KEY                            `sign` (`sign`),
    KEY                            `index1` (`mall_id`,`is_delete`,`sign`,`status`,`goods_warehouse_id`),
    KEY                            `index2` (`is_delete`,`mall_id`,`status`),
    KEY                            `status` (`status`),
    KEY                            `is_delete` (`is_delete`),
    KEY                            `sort` (`sort`),
    KEY                            `created_at` (`created_at`),
    KEY                            `sales` (`sales`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品通用信息表';

-- ----------------------------
-- Table structure for op_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_attr`;
CREATE TABLE `op_goods_attr`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`  int(11) NOT NULL,
    `sign_id`   varchar(255)   NOT NULL DEFAULT '' COMMENT '规格ID标识',
    `stock`     int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
    `price`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '价格',
    `no`        varchar(255)   NOT NULL DEFAULT '' COMMENT '货号',
    `weight`    int(11) NOT NULL DEFAULT '0' COMMENT '重量（克）',
    `pic_url`   varchar(255)   NOT NULL DEFAULT '' COMMENT '规格图片',
    `bar_code`  varchar(255)   NOT NULL DEFAULT '' COMMENT '条形码',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `goods_id` (`goods_id`),
    KEY         `is_delete` (`is_delete`),
    KEY         `index1` (`is_delete`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_attr_template
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_attr_template`;
CREATE TABLE `op_goods_attr_template`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `mch_id`           int(11) NOT NULL DEFAULT '0',
    `attr_group_name`  varchar(255) NOT NULL DEFAULT '' COMMENT '规格名',
    `attr_group_id`    int(11) NOT NULL DEFAULT '0' COMMENT '规格组',
    `attr_list`        longtext     NOT NULL COMMENT '规格值',
    `select_attr_list` longtext     NOT NULL COMMENT '后台 搜索用的',
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`       timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`       timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                `mall_id` (`mall_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_goods_cards
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_cards`;
CREATE TABLE `op_goods_cards`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `mch_id`          int(11) NOT NULL DEFAULT '0',
    `name`            varchar(65)  NOT NULL DEFAULT '',
    `pic_url`         varchar(255) NOT NULL DEFAULT '',
    `description`     text         NOT NULL,
    `expire_type`     tinyint(1) NOT NULL DEFAULT '1' COMMENT '到期类型：1=领取后N天过期，2=指定有效期',
    `expire_day`      int(11) NOT NULL DEFAULT '0' COMMENT '有效天数',
    `begin_time`      timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期开始时间',
    `end_time`        timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期结束时间',
    `total_count`     int(11) NOT NULL DEFAULT '-1' COMMENT '卡券数量',
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp    NOT NULL,
    `deleted_at`      timestamp    NOT NULL,
    `is_delete`       int(11) NOT NULL DEFAULT '0',
    `number`          int(11) NOT NULL DEFAULT '1' COMMENT '卡券可核销总次数',
    `app_share_title` varchar(255) NOT NULL DEFAULT '',
    `app_share_pic`   varchar(255) NOT NULL DEFAULT '',
    `is_allow_send`   int(1) NOT NULL DEFAULT '0' COMMENT '是否允许转赠',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_card_clerk_log
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_card_clerk_log`;
CREATE TABLE `op_goods_card_clerk_log`
(
    `id`             int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_card_id`   int(11) NOT NULL COMMENT '用户卡券ID',
    `clerk_id`       int(11) NOT NULL COMMENT '核销员ID',
    `store_id`       int(11) NOT NULL COMMENT '核销门店ID',
    `use_number`     int(11) NOT NULL COMMENT '核销次数',
    `surplus_number` int(11) NOT NULL COMMENT '剩余次数',
    `clerked_at`     timestamp NOT NULL COMMENT '核销时间',
    PRIMARY KEY (`id`),
    KEY              `user_card_id` (`user_card_id`),
    KEY              `clerk_id` (`clerk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_card_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_card_relation`;
CREATE TABLE `op_goods_card_relation`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`  int(11) NOT NULL,
    `card_id`   int(11) NOT NULL,
    `is_delete` int(11) NOT NULL DEFAULT '0',
    `num`       int(11) NOT NULL DEFAULT '1' COMMENT '卡券数量',
    PRIMARY KEY (`id`),
    KEY         `goods_id` (`goods_id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_cats
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_cats`;
CREATE TABLE `op_goods_cats`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `mch_id`           int(11) NOT NULL DEFAULT '0',
    `parent_id`        int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
    `name`             varchar(45)  NOT NULL DEFAULT '' COMMENT '分类名称',
    `pic_url`          varchar(255) NOT NULL DEFAULT '',
    `sort`             int(11) NOT NULL DEFAULT '100' COMMENT '排序，升序',
    `big_pic_url`      varchar(255) NOT NULL DEFAULT '',
    `advert_pic`       varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片',
    `advert_url`       varchar(255) NOT NULL DEFAULT '' COMMENT '广告链接',
    `status`           tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用:0.禁用|1.启用',
    `created_at`       timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp    NOT NULL,
    `deleted_at`       timestamp    NOT NULL,
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    `is_show`          tinyint(1) DEFAULT '1',
    `advert_open_type` varchar(65)  NOT NULL DEFAULT '' COMMENT '打开方式',
    `advert_params`    text         NOT NULL COMMENT '导航参数',
    PRIMARY KEY (`id`),
    KEY                `index1` (`is_delete`,`status`,`is_show`,`mch_id`,`mall_id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `mch_id` (`mch_id`),
    KEY                `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_cat_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_cat_relation`;
CREATE TABLE `op_goods_cat_relation`
(
    `id`                 int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_warehouse_id` int(11) NOT NULL,
    `cat_id`             int(11) NOT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `goods_warehouse_id` (`goods_warehouse_id`),
    KEY                  `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_coupon_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_coupon_relation`;
CREATE TABLE `op_goods_coupon_relation`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`  int(11) NOT NULL,
    `coupon_id` int(11) NOT NULL,
    `num`       int(11) NOT NULL DEFAULT '1',
    `is_delete` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `goods_id` (`goods_id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='商品赠送优惠券信息';

-- ----------------------------
-- Table structure for op_goods_hot_search
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_hot_search`;
CREATE TABLE `op_goods_hot_search`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `goods_id`   int(11) NOT NULL COMMENT '商品id',
    `title`      varchar(255) NOT NULL COMMENT '热搜词',
    `type`       varchar(100) NOT NULL COMMENT 'goods 自动 hot-search 手动',
    `sort`       smallint(2) NOT NULL DEFAULT '0' COMMENT '排序',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`,`type`,`is_delete`) USING BTREE,
    KEY          `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_goods_member_price
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_member_price`;
CREATE TABLE `op_goods_member_price`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `level`         int(11) NOT NULL,
    `price`         decimal(10, 2) NOT NULL DEFAULT '0.00',
    `goods_attr_id` int(11) NOT NULL,
    `is_delete`     tinyint(4) NOT NULL DEFAULT '0',
    `goods_id`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY             `goods_attr_id` (`goods_attr_id`),
    KEY             `index1` (`is_delete`,`goods_id`,`level`),
    KEY             `goods_id` (`goods_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_params_template
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_params_template`;
CREATE TABLE `op_goods_params_template`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `mch_id`      int(11) NOT NULL DEFAULT '0',
    `name`        varchar(100) NOT NULL COMMENT '模板名称',
    `content`     longtext COMMENT '参数内容',
    `select_data` longtext COMMENT '搜索使用',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `mall_id` (`mall_id`) USING BTREE,
    KEY           `is_delete` (`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_goods_remind
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_remind`;
CREATE TABLE `op_goods_remind`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `goods_id`   int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `is_remind`  smallint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒',
    `is_delete`  smallint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `remind_at`  timestamp NOT NULL COMMENT '提醒时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`) USING BTREE,
    KEY          `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_services
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_services`;
CREATE TABLE `op_goods_services`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(65)   NOT NULL DEFAULT '' COMMENT '服务名称',
    `pic`        varchar(2048) NOT NULL DEFAULT '' COMMENT '商品服务标识',
    `remark`     varchar(255)  NOT NULL DEFAULT '' COMMENT '备注、描述',
    `sort`       int(11) NOT NULL DEFAULT '100',
    `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认服务',
    `created_at` timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp     NOT NULL,
    `deleted_at` timestamp     NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_service_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_service_relation`;
CREATE TABLE `op_goods_service_relation`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `goods_id`   int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `index1` (`goods_id`,`is_delete`) USING BTREE,
    KEY          `index2` (`service_id`,`is_delete`) USING BTREE,
    KEY          `service_id` (`service_id`),
    KEY          `goods_id` (`goods_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_share
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_share`;
CREATE TABLE `op_goods_share`
(
    `id`                      int(11) NOT NULL AUTO_INCREMENT,
    `share_commission_first`  decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例',
    `share_commission_second` decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例',
    `share_commission_third`  decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例',
    `goods_id`                int(11) NOT NULL,
    `goods_attr_id`           int(11) NOT NULL DEFAULT '0',
    `is_delete`               tinyint(4) NOT NULL DEFAULT '0',
    `level`                   int(11) NOT NULL DEFAULT '0' COMMENT '分销商等级',
    PRIMARY KEY (`id`),
    KEY                       `goods_attr_id` (`goods_attr_id`),
    KEY                       `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_goods_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `op_goods_warehouse`;
CREATE TABLE `op_goods_warehouse`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `name`           varchar(255)                    NOT NULL COMMENT '商品名称',
    `subtitle`       varchar(255)                    NOT NULL DEFAULT '' COMMENT '副标题',
    `original_price` decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '原价',
    `cost_price`     decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '成本价',
    `detail`         longtext                        NOT NULL COMMENT '商品详情，图文',
    `cover_pic`      varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商品缩略图',
    `pic_url`        text CHARACTER SET utf8 NOT NULL COMMENT '商品轮播图',
    `video_url`      varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '商品视频',
    `unit`           varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '件' COMMENT '单位',
    `created_at`     timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp                       NOT NULL,
    `deleted_at`     timestamp                       NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `type`           varchar(255)                    NOT NULL DEFAULT 'goods' COMMENT '商品类型：goods--实体商品 ecard--电子卡密',
    `ecard_id`       int(11) NOT NULL DEFAULT '0' COMMENT '卡密id',
    PRIMARY KEY (`id`) USING BTREE,
    KEY              `store_id` (`mall_id`) USING BTREE,
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='商品';

-- ----------------------------
-- Table structure for op_home_block
-- ----------------------------
DROP TABLE IF EXISTS `op_home_block`;
CREATE TABLE `op_home_block`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(65) NOT NULL DEFAULT '',
    `value`      text        NOT NULL,
    `type`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '样式类型：1.默认|2.样式一|3.样式二',
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NOT NULL,
    `deleted_at` timestamp   NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_home_nav
-- ----------------------------
DROP TABLE IF EXISTS `op_home_nav`;
CREATE TABLE `op_home_nav`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(65)  NOT NULL DEFAULT '' COMMENT '导航名称',
    `url`        varchar(255) NOT NULL DEFAULT '' COMMENT '导航链接',
    `open_type`  varchar(65)  NOT NULL DEFAULT '' COMMENT '打开方式',
    `icon_url`   varchar(255) NOT NULL DEFAULT '' COMMENT '导航图标',
    `sort`       int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0.隐藏|1.显示',
    `params`     text         NOT NULL COMMENT '导航参数',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `sign`       varchar(65)  NOT NULL DEFAULT '' COMMENT '插件标识',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_import_data
-- ----------------------------
DROP TABLE IF EXISTS `op_import_data`;
CREATE TABLE `op_import_data`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `mch_id`        int(11) NOT NULL DEFAULT '0',
    `user_id`       int(11) NOT NULL COMMENT '操作账户ID',
    `status`        tinyint(4) NOT NULL COMMENT '导入状态|1.全部失败|2.部分失败|3.全部成功',
    `file_name`     varchar(191) NOT NULL DEFAULT '' COMMENT '导入文件名',
    `count`         int(11) NOT NULL COMMENT '导入总数量',
    `success_count` int(11) NOT NULL,
    `error_count`   int(11) NOT NULL,
    `created_at`    timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    timestamp    NOT NULL,
    `deleted_at`    timestamp    NOT NULL,
    `is_delete`     int(11) NOT NULL DEFAULT '0',
    `type`          tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.商品导入|2.分类导入',
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_log
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_log`;
CREATE TABLE `op_integral_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        tinyint(1) NOT NULL COMMENT '类型：1=收入，2=支出',
    `integral`    int(11) NOT NULL COMMENT '变动积分',
    `desc`        varchar(255) NOT NULL DEFAULT '' COMMENT '变动说明',
    `custom_desc` longtext     NOT NULL COMMENT '自定义详细说明|记录',
    `order_no`    varchar(255) NOT NULL DEFAULT '' COMMENT '订单号',
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `user_id` (`user_id`),
    KEY           `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_banners
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_banners`;
CREATE TABLE `op_integral_mall_banners`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_cats
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_cats`;
CREATE TABLE `op_integral_mall_cats`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `cat_id`     int(11) NOT NULL,
    `sort`       int(11) NOT NULL DEFAULT '100',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_coupons
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_coupons`;
CREATE TABLE `op_integral_mall_coupons`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `coupon_id`    int(11) NOT NULL,
    `exchange_num` int(11) NOT NULL DEFAULT '-1' COMMENT '兑换次数-1.不限制',
    `integral_num` int(11) NOT NULL COMMENT '所需兑换积分',
    `send_count`   int(11) NOT NULL COMMENT '发放优惠券总数',
    `price`        decimal(10, 2) NOT NULL COMMENT '价格',
    `created_at`   timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp      NOT NULL,
    `deleted_at`   timestamp      NOT NULL,
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_coupons_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_coupons_orders`;
CREATE TABLE `op_integral_mall_coupons_orders`
(
    `id`                        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`                   int(11) NOT NULL COMMENT '用户ID',
    `mall_id`                   int(11) NOT NULL,
    `order_no`                  varchar(255)   NOT NULL DEFAULT '',
    `integral_mall_coupon_id`   int(11) NOT NULL COMMENT '积分商城优惠券ID',
    `integral_mall_coupon_info` text           NOT NULL COMMENT '积分商城优惠券信息',
    `user_coupon_id`            int(11) NOT NULL COMMENT '关联用户优惠券ID',
    `price`                     decimal(11, 2) NOT NULL COMMENT '优惠券价格',
    `integral_num`              int(11) NOT NULL COMMENT '优惠券积分',
    `is_pay`                    tinyint(1) NOT NULL DEFAULT '0',
    `pay_time`                  timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `pay_type`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付',
    `token`                     varchar(255)   NOT NULL,
    `created_at`                timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                timestamp      NOT NULL,
    `deleted_at`                timestamp      NOT NULL,
    `is_delete`                 tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`),
    KEY                         `user_id` (`user_id`),
    KEY                         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_coupons_user
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_coupons_user`;
CREATE TABLE `op_integral_mall_coupons_user`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`                 int(11) NOT NULL COMMENT '用户ID',
    `mall_id`                 int(11) NOT NULL,
    `integral_mall_coupon_id` int(11) NOT NULL COMMENT '积分商城优惠券ID',
    `user_coupon_id`          int(11) NOT NULL COMMENT '关联用户优惠券ID',
    `created_at`              timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`              timestamp NOT NULL,
    `deleted_at`              timestamp NOT NULL,
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `mall_id` (`mall_id`),
    KEY                       `user_id` (`user_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_coupon_order_submit_result
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_coupon_order_submit_result`;
CREATE TABLE `op_integral_mall_coupon_order_submit_result`
(
    `id`    int(11) unsigned NOT NULL AUTO_INCREMENT,
    `token` varchar(32) NOT NULL DEFAULT '',
    `data`  longtext    NOT NULL,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_goods`;
CREATE TABLE `op_integral_mall_goods`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`     int(11) NOT NULL,
    `mall_id`      int(11) NOT NULL,
    `is_home`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '放置首页0.否|1.是',
    `integral_num` int(11) NOT NULL DEFAULT '0',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_goods_attr`;
CREATE TABLE `op_integral_mall_goods_attr`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `integral_num`  int(11) NOT NULL DEFAULT '0' COMMENT '商品所需积分',
    `goods_id`      int(11) NOT NULL,
    `goods_attr_id` int(11) NOT NULL,
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY             `goods_id` (`goods_id`),
    KEY             `goods_attr_id` (`goods_attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_orders`;
CREATE TABLE `op_integral_mall_orders`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `order_id`     int(11) NOT NULL,
    `token`        varchar(255) NOT NULL DEFAULT '',
    `integral_num` int(11) NOT NULL COMMENT '商品所需积分',
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`   timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_integral_mall_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_integral_mall_setting`;
CREATE TABLE `op_integral_mall_setting`
(
    `id`                        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `is_share`                  int(11) NOT NULL DEFAULT '0',
    `is_sms`                    int(11) NOT NULL DEFAULT '0',
    `is_mail`                   int(11) NOT NULL DEFAULT '0',
    `is_print`                  int(11) NOT NULL DEFAULT '0',
    `is_territorial_limitation` int(11) NOT NULL DEFAULT '0',
    `desc`                      text      NOT NULL COMMENT '积分说明',
    `created_at`                timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                timestamp NOT NULL,
    `payment_type`              longtext  NOT NULL COMMENT '支付方式',
    `send_type`                 longtext  NOT NULL COMMENT '发货方式',
    `goods_poster`              longtext  NOT NULL COMMENT '自定义海报',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_live_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_live_goods`;
CREATE TABLE `op_live_goods`
(
    `id`       int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`  int(11) NOT NULL,
    `goods_id` int(11) NOT NULL,
    `audit_id` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY        `index_name` (`goods_id`,`audit_id`,`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_lottery
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery`;
CREATE TABLE `op_lottery`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`              int(11) NOT NULL,
    `goods_id`             int(11) NOT NULL COMMENT '规格',
    `status`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
    `type`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未完成 1已完成 2超限 3过期',
    `stock`                int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `start_at`             timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
    `end_at`               timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
    `join_min_num`         int(11) NOT NULL DEFAULT '0' COMMENT '参加最少人数限制',
    `sort`                 int(11) NOT NULL DEFAULT '1' COMMENT '排序',
    `is_delete`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`           timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`           timestamp NOT NULL,
    `deleted_at`           timestamp NOT NULL,
    `participant`          int(11) NOT NULL DEFAULT '0' COMMENT '参与人',
    `invitee`              int(11) NOT NULL DEFAULT '0' COMMENT '被邀请人',
    `code_num`             int(11) NOT NULL DEFAULT '0' COMMENT '抽奖券码数量',
    `buy_goods_id`         int(11) NOT NULL COMMENT '购买商品id',
    `deplete_integral_num` int(11) NOT NULL DEFAULT '0' COMMENT '消耗积分',
    PRIMARY KEY (`id`),
    KEY                    `mall_id` (`mall_id`),
    KEY                    `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_lottery_banner
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery_banner`;
CREATE TABLE `op_lottery_banner`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_lottery_default
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery_default`;
CREATE TABLE `op_lottery_default`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `lottery_id` int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_lottery_log
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery_log`;
CREATE TABLE `op_lottery_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `lottery_id` int(11) NOT NULL,
    `status`     smallint(1) NOT NULL DEFAULT '0' COMMENT '0未抽奖 1待开奖 2未中奖 3中奖 4已领取 ',
    `goods_id`   int(11) NOT NULL COMMENT '规格id',
    `child_id`   int(11) NOT NULL DEFAULT '0' COMMENT '受邀请userid',
    `lucky_code` varchar(255) NOT NULL COMMENT '幸运码',
    `raffled_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '领取时间',
    `obtain_at`  timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `token`      varchar(255) NOT NULL DEFAULT '' COMMENT '订单表token',
    PRIMARY KEY (`id`),
    KEY          `lottery_id` (`lottery_id`) USING BTREE,
    KEY          `user_id` (`user_id`) USING BTREE,
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_lottery_order
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery_order`;
CREATE TABLE `op_lottery_order`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `lottery_log_id` int(11) NOT NULL,
    `order_id`       int(11) NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`     timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_lottery_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_lottery_setting`;
CREATE TABLE `op_lottery_setting`
(
    `id`                         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                    int(11) NOT NULL,
    `type`                       tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：分享即送 1： 被分享人参与抽奖',
    `title`                      varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题',
    `rule`                       longtext     NOT NULL COMMENT '规则',
    `created_at`                 timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `payment_type`               longtext     NOT NULL COMMENT '支付方式',
    `send_type`                  longtext     NOT NULL COMMENT '发货方式',
    `goods_poster`               longtext     NOT NULL COMMENT '自定义海报',
    `is_sms`                     tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
    `is_mail`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启邮件提醒',
    `is_print`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启打印',
    `bg_pic`                     varchar(255) NOT NULL DEFAULT '' COMMENT '背景图',
    `bg_color`                   varchar(255) NOT NULL DEFAULT '' COMMENT '背景颜色',
    `bg_color_type`              varchar(255) NOT NULL DEFAULT '' COMMENT '背景颜色类型',
    `bg_gradient_color`          varchar(255) NOT NULL DEFAULT '' COMMENT '背景渐变颜色',
    `cs_status`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启客服提示',
    `cs_prompt_pic`              varchar(255) NOT NULL DEFAULT '' COMMENT '客服提示图片',
    `cs_wechat`                  longtext     NOT NULL COMMENT '客服微信号',
    `cs_wechat_flock_qrcode_pic` longtext     NOT NULL COMMENT '微信群',
    PRIMARY KEY (`id`),
    KEY                          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mail_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_mail_setting`;
CREATE TABLE `op_mail_setting`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL DEFAULT '-1',
    `mch_id`        int(11) NOT NULL DEFAULT '0',
    `send_mail`     longtext CHARACTER SET utf8 NOT NULL COMMENT '发件人邮箱',
    `send_pwd`      varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '授权码',
    `send_name`     varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '发件人名称',
    `receive_mail`  longtext CHARACTER SET utf8 NOT NULL COMMENT '收件人邮箱 多个用英文逗号隔开',
    `status`        int(11) NOT NULL DEFAULT '0' COMMENT '是否开启邮件通知 0--关闭 1--开启',
    `is_delete`     int(11) NOT NULL DEFAULT '0',
    `created_at`    timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    timestamp                       NOT NULL,
    `deleted_at`    timestamp                       NOT NULL,
    `show_type`     longtext                        NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单',
    `send_platform` varchar(200)                             DEFAULT 'smtp.qq.com' COMMENT '发送平台',
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mall
-- ----------------------------
DROP TABLE IF EXISTS `op_mall`;
CREATE TABLE `op_mall`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(64) NOT NULL DEFAULT '',
    `user_id`    int(11) unsigned NOT NULL,
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NOT NULL,
    `deleted_at` timestamp   NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `is_recycle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回收',
    `is_disable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用',
    `expired_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '账户过期时间',
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商城';

-- ----------------------------
-- Table structure for op_mall_banner_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_banner_relation`;
CREATE TABLE `op_mall_banner_relation`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL COMMENT '轮播图id',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mall_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_goods`;
CREATE TABLE `op_mall_goods`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `goods_id`      int(11) NOT NULL,
    `is_quick_shop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否快速购买',
    `is_sell_well`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热销',
    `is_negotiable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否面议商品',
    `created_at`    timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    timestamp NULL DEFAULT NULL,
    `deleted_at`    timestamp NULL DEFAULT NULL,
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY             `index1` (`goods_id`),
    KEY             `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商城商品额外信息表';

-- ----------------------------
-- Table structure for op_mall_members
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_members`;
CREATE TABLE `op_mall_members`
(
    `id`             int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `level`          int(11) unsigned NOT NULL COMMENT '会员等级',
    `name`           varchar(65)    NOT NULL DEFAULT '' COMMENT '等级名称',
    `auto_update`    tinyint(1) NOT NULL COMMENT '是否开启自动升级',
    `money`          decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '会员完成订单金额满足则升级',
    `discount`       decimal(11, 1) NOT NULL DEFAULT '0.0' COMMENT '会员折扣',
    `status`         tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0--禁用 1--启用',
    `pic_url`        varchar(255)   NOT NULL DEFAULT '' COMMENT '会员图片',
    `is_purchase`    tinyint(1) NOT NULL COMMENT '会员是否可购买',
    `price`          decimal(11, 2) NOT NULL COMMENT '购买会员价格',
    `rules`          mediumtext     NOT NULL COMMENT '会员规则',
    `created_at`     timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp      NOT NULL,
    `deleted_at`     timestamp      NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `bg_pic_url`     varchar(255)   NOT NULL,
    `condition_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '升级条件：1-累积金额，2-购买商品',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `level` (`level`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mall_member_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_member_orders`;
CREATE TABLE `op_mall_member_orders`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) NOT NULL,
    `mall_id`    int(11) NOT NULL,
    `order_no`   varchar(30)    NOT NULL DEFAULT '' COMMENT '订单号',
    `pay_price`  decimal(10, 2) NOT NULL COMMENT '支付金额',
    `pay_type`   tinyint(1) NOT NULL COMMENT '支付方式 1.线上支付',
    `is_pay`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付',
    `pay_time`   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
    `detail`     mediumtext,
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mall_member_rights
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_member_rights`;
CREATE TABLE `op_mall_member_rights`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `member_id` int(11) NOT NULL,
    `title`     varchar(65)  NOT NULL DEFAULT '',
    `content`   varchar(255) NOT NULL DEFAULT '',
    `pic_url`   varchar(255) NOT NULL DEFAULT '',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mall_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_setting`;
CREATE TABLE `op_mall_setting`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `key`       varchar(65) NOT NULL DEFAULT '',
    `value`     mediumtext,
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `mall_id` (`mall_id`),
    KEY         `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch
-- ----------------------------
DROP TABLE IF EXISTS `op_mch`;
CREATE TABLE `op_mch`
(
    `id`                int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `user_id`           int(11) NOT NULL DEFAULT '0',
    `status`            tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否营业0.否|1.是',
    `is_recommend`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '好店推荐：0.不推荐|1.推荐',
    `review_status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态：0=待审核，1.审核通过.2=审核不通过',
    `review_remark`     varchar(255)   NOT NULL DEFAULT '' COMMENT '审核结果、备注',
    `review_time`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '审核时间',
    `realname`          varchar(65)    NOT NULL DEFAULT '' COMMENT '真实姓名',
    `wechat`            varchar(65)    NOT NULL DEFAULT '' COMMENT '微信号',
    `mobile`            varchar(255)   NOT NULL DEFAULT '' COMMENT '手机号码',
    `mch_common_cat_id` int(11) NOT NULL COMMENT '商户所属类目',
    `transfer_rate`     int(11) NOT NULL DEFAULT '0' COMMENT '商户手续费',
    `account_money`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
    `sort`              int(11) NOT NULL DEFAULT '100' COMMENT '店铺排序|升序',
    `form_data`         mediumtext     NOT NULL,
    `created_at`        timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp      NOT NULL,
    `deleted_at`        timestamp      NOT NULL,
    `is_delete`         tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_account_log
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_account_log`;
CREATE TABLE `op_mch_account_log`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL,
    `money`      decimal(11, 2) NOT NULL COMMENT '金额',
    `desc`       text           NOT NULL COMMENT '备注说明',
    `type`       tinyint(1) NOT NULL COMMENT '类型：1=收入，2=支出',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_cash
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_cash`;
CREATE TABLE `op_mch_cash`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `mch_id`          int(11) NOT NULL COMMENT '多商户ID',
    `money`           decimal(10, 2) NOT NULL COMMENT '提现金额',
    `order_no`        varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `status`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现状态：0=待处理，1=同意，2=拒绝',
    `transfer_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0.待转账 | 1.已转账  | 2.拒绝转账',
    `type`            varchar(65)    NOT NULL DEFAULT '0' COMMENT 'wx 微信| alipay 支付宝 | bank 银行卡 | balance 余额',
    `type_data`       varchar(600)   NOT NULL DEFAULT '' COMMENT '不同提现类型，提交的数据',
    `virtual_type`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '实际上打款方式',
    `created_at`      timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp      NOT NULL,
    `deleted_at`      timestamp      NOT NULL,
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    `content`         longtext COMMENT '备注',
    PRIMARY KEY (`id`),
    KEY               `mch_id` (`mch_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_common_cat
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_common_cat`;
CREATE TABLE `op_mch_common_cat`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) NOT NULL DEFAULT '' COMMENT '类目名称',
    `sort`       int(11) NOT NULL DEFAULT '100' COMMENT '排序：升序',
    `status`     tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_goods`;
CREATE TABLE `op_mch_goods`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mch_id`    int(11) NOT NULL,
    `mall_id`   int(11) NOT NULL,
    `goods_id`  int(11) NOT NULL,
    `status`    tinyint(4) NOT NULL DEFAULT '0' COMMENT '0.申请上架|1.申请中|2.同意上架|3.拒绝上架',
    `sort`      int(11) NOT NULL DEFAULT '100' COMMENT '商户的排序',
    `remark`    varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
    `is_delete` tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_mall_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_mall_setting`;
CREATE TABLE `op_mch_mall_setting`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL,
    `is_share`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销0.否|1.是',
    `is_coupon`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启优惠券0.否|1.是',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_order
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_order`;
CREATE TABLE `op_mch_order`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id`    int(11) NOT NULL,
    `is_transfer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否转入商户0.否|1.是',
    `updated_at`  timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY           `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_setting`;
CREATE TABLE `op_mch_setting`
(
    `id`                        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `mch_id`                    int(11) NOT NULL,
    `is_share`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销0.否|1.是',
    `is_sms`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启短信提醒',
    `is_mail`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启邮件通知',
    `is_print`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启订单打印',
    `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '区域购买限制',
    `send_type`                 longtext     NOT NULL COMMENT '发货方式',
    `created_at`                timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_web_service`            tinyint(1) NOT NULL DEFAULT '0',
    `web_service_url`           varchar(255) NOT NULL DEFAULT '',
    `web_service_pic`           varchar(255) NOT NULL DEFAULT '',
    `web_service_type`          tinyint(1) default 1 COMMENT '1：其它客服，2：企业微信客服',
    `enterprise_wechat_id`      varchar(150)          default null COMMENT '企业微信id',
    PRIMARY KEY (`id`),
    KEY                         `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_mch_visit_log
-- ----------------------------
DROP TABLE IF EXISTS `op_mch_visit_log`;
CREATE TABLE `op_mch_visit_log`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `num`        int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mch_id` (`mch_id`),
    KEY          `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_miaosha_activitys
-- ----------------------------
DROP TABLE IF EXISTS `op_miaosha_activitys`;
CREATE TABLE `op_miaosha_activitys`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '秒杀活动状态0.关闭|1.开启',
    `open_date`  date      NOT NULL DEFAULT '0000-00-00' COMMENT '活动开始时间',
    `end_date`   date      NOT NULL DEFAULT '0000-00-00' COMMENT '活动结束时间',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_miaosha_banners
-- ----------------------------
DROP TABLE IF EXISTS `op_miaosha_banners`;
CREATE TABLE `op_miaosha_banners`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_miaosha_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_miaosha_goods`;
CREATE TABLE `op_miaosha_goods`
(
    `id`                  int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`             int(11) NOT NULL,
    `goods_id`            int(11) NOT NULL,
    `goods_warehouse_id`  int(11) NOT NULL,
    `open_time`           tinyint(1) NOT NULL COMMENT '开放时间',
    `open_date`           date NOT NULL,
    `buy_limit`           int(11) NOT NULL DEFAULT '-1' COMMENT '限单 -1|不限单',
    `virtual_miaosha_num` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟秒杀量',
    `is_delete`           tinyint(1) NOT NULL DEFAULT '0',
    `activity_id`         int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
    PRIMARY KEY (`id`),
    KEY                   `index1` (`is_delete`,`open_date`,`open_time`),
    KEY                   `index2` (`is_delete`,`goods_id`),
    KEY                   `mall_id` (`mall_id`),
    KEY                   `goods_id` (`goods_id`),
    KEY                   `goods_warehouse_id` (`goods_warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_miaosha_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_miaosha_setting`;
CREATE TABLE `op_miaosha_setting`
(
    `id`                        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `over_time`                 int(11) NOT NULL DEFAULT '1' COMMENT '未支付订单取消时间',
    `is_share`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否短信提醒',
    `is_mail`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启邮件通知',
    `is_print`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启订单打印',
    `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '区域购买限制',
    `open_time`                 text      NOT NULL COMMENT '秒杀开放时间',
    `created_at`                timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `payment_type`              longtext  NOT NULL,
    `send_type`                 longtext  NOT NULL,
    `goods_poster`              longtext  NOT NULL COMMENT '自定义海报',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='秒杀设置';

-- ----------------------------
-- Table structure for op_mp_template_record
-- ----------------------------
DROP TABLE IF EXISTS `op_mp_template_record`;
CREATE TABLE `op_mp_template_record`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `open_id`    varchar(255) NOT NULL,
    `status`     int(1) NOT NULL DEFAULT '0' COMMENT '模板消息是否发送成功0--失败|1--成功',
    `data`       longtext     NOT NULL COMMENT '模板消息内容',
    `error`      longtext     NOT NULL COMMENT '错误信息',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `token`      varchar(255) NOT NULL DEFAULT '' COMMENT '模板消息发送标示',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `open_id` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='模板消息发送记录表';

-- ----------------------------
-- Table structure for op_option
-- ----------------------------
DROP TABLE IF EXISTS `op_option`;
CREATE TABLE `op_option`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `group`      varchar(100) NOT NULL DEFAULT '',
    `name`       varchar(150) NOT NULL,
    `value`      longtext     NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `name` (`name`),
    KEY          `mall_id` (`mall_id`),
    KEY          `mch_id` (`mch_id`),
    KEY          `group` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_order
-- ----------------------------
DROP TABLE IF EXISTS `op_order`;
CREATE TABLE `op_order`
(
    `id`                         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                    int(11) NOT NULL,
    `user_id`                    int(11) NOT NULL,
    `mch_id`                     int(11) NOT NULL DEFAULT '0' COMMENT '多商户id，0表示商城订单',
    `order_no`                   varchar(100)   NOT NULL DEFAULT '' COMMENT '订单号',
    `total_price`                decimal(10, 2) NOT NULL COMMENT '订单总金额(含运费)',
    `total_pay_price`            decimal(10, 2) NOT NULL COMMENT '实际支付总费用(含运费）',
    `express_original_price`     decimal(10, 2) NOT NULL COMMENT '运费(后台修改前)',
    `express_price`              decimal(10, 2) NOT NULL COMMENT '运费(后台修改后)',
    `total_goods_price`          decimal(10, 2) NOT NULL COMMENT '订单商品总金额(优惠后)',
    `total_goods_original_price` decimal(10, 2) NOT NULL COMMENT '订单商品总金额(优惠前)',
    `member_discount_price`      decimal(10, 2) NOT NULL COMMENT '会员优惠价格(正数表示优惠，负数表示加价)',
    `full_reduce_price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '满减活动优惠价格',
    `use_user_coupon_id`         int(11) NOT NULL COMMENT '使用的用户优惠券id',
    `coupon_discount_price`      decimal(10, 2) NOT NULL COMMENT '优惠券优惠金额',
    `use_integral_num`           int(11) NOT NULL COMMENT '使用积分数量',
    `integral_deduction_price`   decimal(10, 2) NOT NULL COMMENT '积分抵扣金额',
    `name`                       varchar(65)    NOT NULL DEFAULT '' COMMENT '收件人姓名',
    `mobile`                     varchar(255)   NOT NULL DEFAULT '' COMMENT '收件人手机号',
    `address`                    varchar(255)   NOT NULL DEFAULT '' COMMENT '收件人地址',
    `remark`                     varchar(255)   NOT NULL DEFAULT '' COMMENT '用户订单备注',
    `order_form`                 longtext COMMENT '自定义表单（JSON）',
    `words`                      varchar(255)   NOT NULL DEFAULT '' COMMENT '商家留言',
    `seller_remark`              varchar(255)   NOT NULL DEFAULT '' COMMENT '商家订单备注',
    `is_pay`                     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付：0.未支付|1.已支付',
    `pay_type`                   tinyint(4) NOT NULL COMMENT '支付方式：1.在线支付 2.货到付款 3.余额支付',
    `pay_time`                   timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
    `is_send`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发货：0.未发货|1.已发货',
    `send_time`                  timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发货时间',
--     `customer_name`              varchar(65)             DEFAULT '' COMMENT '京东商家编号',
--     `express`                    varchar(65)    NOT NULL DEFAULT '' COMMENT '物流公司',
--     `express_no`                 varchar(255)   NOT NULL DEFAULT '' COMMENT '物流订单号',
    `is_sale`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否过售后时间',
    `is_confirm`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '收货状态：0.未收货|1.已收货',
    `confirm_time`               timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '确认收货时间',
    `cancel_status`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单取消状态：0.未取消|1.已取消|2.申请取消',
    `cancel_time`                timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '订单取消时间',
    `created_at`                 timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                 timestamp      NOT NULL,
    `deleted_at`                 timestamp      NOT NULL,
    `is_delete`                  tinyint(1) NOT NULL DEFAULT '0',
    `is_recycle`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加入回收站 0.否|1.是',
    `send_type`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送方式：0--快递配送 1--到店自提 2--同城配送 3--无配送',
    `offline_qrcode`             varchar(255)   NOT NULL DEFAULT '' COMMENT '核销码',
    `clerk_id`                   int(11) NOT NULL DEFAULT '0' COMMENT '核销员ID',
    `store_id`                   int(11) NOT NULL DEFAULT '0' COMMENT '自提门店ID',
    `sign`                       varchar(100)   NOT NULL DEFAULT '' COMMENT '订单标识，用于区分插件',
    `token`                      varchar(32)    NOT NULL DEFAULT '',
    `support_pay_types`          longtext COMMENT '支持的支付方式，空表示支持系统设置支持的所有方式',
    `is_comment`                 tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评价0.否|1.是',
    `comment_time`               timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `sale_status`                tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否申请售后',
    `status`                     tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态|1.已完成|0.进行中不能对订单进行任何操作',
    `back_price`                 decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '后台优惠(正数表示优惠，负数表示加价)',
    `auto_cancel_time`           timestamp NULL DEFAULT NULL COMMENT '自动取消时间',
    `auto_confirm_time`          timestamp NULL DEFAULT NULL COMMENT '自动确认收货时间',
    `auto_sales_time`            timestamp NULL DEFAULT NULL COMMENT '自动售后时间',
    `distance`                   int(11) DEFAULT '-1' COMMENT '同城配送距离，-1不在范围内，正数为距离KM',
--     `city_mobile`                varchar(100)            DEFAULT '' COMMENT '同城配送联系方式',
    `location`                   varchar(255)            DEFAULT NULL,
--     `city_name`                  varchar(255)            DEFAULT NULL,
--     `city_info`                  varchar(255)            DEFAULT NULL,
    `cancel_data`                text COMMENT '订单申请退款数据',
    `platform`                   varchar(32)    NOT NULL DEFAULT '' COMMENT 'wxapp 微信小程序',
    `replace_user_id`            int(10) DEFAULT 0 COMMENT '代付用户id',
    PRIMARY KEY (`id`),
    KEY                          `order_no` (`order_no`),
    KEY                          `is_pay` (`is_pay`),
    KEY                          `is_send` (`is_send`),
    KEY                          `is_sale` (`is_sale`),
    KEY                          `is_confirm` (`is_confirm`),
    KEY                          `is_delete` (`is_delete`),
    KEY                          `is_recycle` (`is_recycle`),
    KEY                          `token` (`token`),
    KEY                          `is_comment` (`is_comment`),
    KEY                          `status` (`status`),
    KEY                          `sale_status` (`sale_status`),
    KEY                          `sign` (`sign`),
    KEY                          `store_id` (`store_id`),
    KEY                          `user_id` (`user_id`,`is_delete`),
    KEY                          `index5` (`mall_id`,`is_delete`,`cancel_status`,`is_pay`,`pay_type`,`mch_id`) USING BTREE,
    KEY                          `index_3` (`mall_id`,`is_pay`,`is_recycle`,`created_at`,`mch_id`) USING BTREE,
    KEY                          `index_4` (`mall_id`,`is_confirm`,`is_send`,`is_recycle`,`mch_id`) USING BTREE,
    KEY                          `index_5` (`is_send`,`mall_id`,`is_recycle`,`status`,`mch_id`) USING BTREE,
    KEY                          `index_6` (`mall_id`,`is_sale`,`is_confirm`,`is_recycle`,`mch_id`) USING BTREE,
    KEY                          `index_7` (`mall_id`,`is_pay`,`is_send`,`is_recycle`,`mch_id`) USING BTREE,
    KEY                          `index_8` (`mall_id`,`is_recycle`,`status`,`is_delete`,`mch_id`) USING BTREE,
    KEY                          `index1` (`mall_id`,`is_delete`,`is_pay`,`pay_type`,`cancel_status`,`mch_id`) USING BTREE,
    KEY                          `index4` (`mall_id`,`is_delete`,`cancel_status`,`pay_type`,`mch_id`) USING BTREE,
    KEY                          `index_0` (`user_id`,`mall_id`,`is_send`,`is_recycle`,`mch_id`) USING BTREE,
    KEY                          `index_1` (`is_recycle`,`is_delete`,`cancel_status`,`mch_id`) USING BTREE,
    KEY                          `index_2` (`mall_id`,`is_pay`,`is_recycle`,`is_delete`,`mch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_order_clerk
-- ----------------------------
DROP TABLE IF EXISTS `op_order_clerk`;
CREATE TABLE `op_order_clerk`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `affirm_pay_type` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '确认收款类型|1.小程序收款|2.后台收款',
    `clerk_type`      tinyint(1) NOT NULL DEFAULT '-1' COMMENT '确认核销类型|1.小程序核销|2.后台核销',
    `clerk_remark`    varchar(255) NOT NULL DEFAULT '' COMMENT '核销备注',
    `mall_id`         int(11) NOT NULL,
    `order_id`        int(11) NOT NULL,
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp    NOT NULL,
    `deleted_at`      timestamp    NOT NULL,
    `is_delete`       tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY               `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_comments
-- ----------------------------
DROP TABLE IF EXISTS `op_order_comments`;
CREATE TABLE `op_order_comments`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `mch_id`             int(11) NOT NULL DEFAULT '0',
    `order_id`           int(11) NOT NULL,
    `order_detail_id`    int(11) NOT NULL,
    `user_id`            int(11) NOT NULL,
    `score`              tinyint(4) NOT NULL COMMENT '评分：1=差评，2=中评，3=好',
    `content`            text         NOT NULL COMMENT '评价内容',
    `pic_url`            text         NOT NULL COMMENT '评价图片',
    `is_show`            tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：0.不显示|1.显示',
    `is_virtual`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否虚拟用户',
    `virtual_user`       varchar(255) NOT NULL DEFAULT '' COMMENT '虚拟用户名',
    `virtual_avatar`     varchar(255) NOT NULL DEFAULT '' COMMENT '虚拟头像',
    `virtual_time`       timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '虚拟评价时间',
    `goods_id`           int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
    `goods_warehouse_id` int(11) NOT NULL COMMENT '商品库ID',
    `sign`               varchar(255) NOT NULL DEFAULT '',
    `reply_content`      text         NOT NULL COMMENT '商家回复内容',
    `created_at`         timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp    NOT NULL,
    `deleted_at`         timestamp    NOT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `is_anonymous`       tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否匿名 0.否|1.是',
    `is_top`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶0.否|1.是',
    `goods_info`         longtext COMMENT '商品信息',
    `attr_id`            int(11) NOT NULL DEFAULT '0' COMMENT '规格',
    PRIMARY KEY (`id`),
    KEY                  `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_comments_templates
-- ----------------------------
DROP TABLE IF EXISTS `op_order_comments_templates`;
CREATE TABLE `op_order_comments_templates`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `type`       tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板类型:1.好评|2.中评|3.差评',
    `title`      varchar(65)  NOT NULL DEFAULT '' COMMENT '标题',
    `content`    varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `op_order_detail`;
CREATE TABLE `op_order_detail`
(
    `id`                    int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id`              int(11) NOT NULL,
    `goods_id`              int(11) NOT NULL,
    `num`                   int(11) NOT NULL COMMENT '购买商品数量',
    `unit_price`            decimal(10, 2) NOT NULL COMMENT '商品单价',
    `total_original_price`  decimal(10, 2) NOT NULL COMMENT '商品原总价(优惠前)',
    `total_price`           decimal(10, 2) NOT NULL COMMENT '商品总价(优惠后)',
    `member_discount_price` decimal(10, 2) NOT NULL COMMENT '会员优惠金额(正数表示优惠，负数表示加价)',
    `erase_price`           decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '抹零价格',
    `goods_info`            longtext       NOT NULL COMMENT '购买商品信息',
    `is_delete`             tinyint(1) NOT NULL DEFAULT '0',
    `created_at`            timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            timestamp      NOT NULL,
    `deleted_at`            timestamp      NOT NULL,
    `is_refund`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否退款',
    `refund_status`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '售后状态 0--未售后 1--售后中 2--售后结束',
    `back_price`            decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '后台优惠(正数表示优惠，负数表示加价)',
    `sign`                  varchar(255)   NOT NULL DEFAULT '' COMMENT '订单详情标识，用于区分插件',
    `goods_no`              varchar(60)    NOT NULL DEFAULT '' COMMENT '商品货号',
    `form_data`             longtext COMMENT '自定义表单提交的数据',
    `form_id`               int(11) DEFAULT '0' COMMENT '自定义表单的id',
    `goods_type`            varchar(255)   NOT NULL DEFAULT 'goods' COMMENT '商品类型',
    PRIMARY KEY (`id`),
    KEY                     `order_id` (`order_id`),
    KEY                     `index1` (`goods_id`,`is_refund`,`order_id`),
    KEY                     `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_detail_express
-- ----------------------------
DROP TABLE IF EXISTS `op_order_detail_express`;
CREATE TABLE `op_order_detail_express`
(
    `id`                int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `mch_id`            int(11) NOT NULL,
    `order_id`          int(11) NOT NULL COMMENT '订单ID',
    `express`           varchar(65)  NOT NULL DEFAULT '',
    `send_type`         tinyint(1) NOT NULL COMMENT '1.快递|2.其它方式',
    `express_no`        varchar(255) NOT NULL DEFAULT '',
    `merchant_remark`   varchar(255) NOT NULL DEFAULT '' COMMENT '商家留言',
    `express_content`   varchar(255) NOT NULL DEFAULT '' COMMENT '物流内容',
    `is_delete`         tinyint(4) NOT NULL DEFAULT '0',
    `created_at`        timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp    NOT NULL,
    `deleted_at`        timestamp    NOT NULL,
    `customer_name`     varchar(255) NOT NULL DEFAULT '' COMMENT '京东物流编号',
    `express_single_id` int(11) NOT NULL DEFAULT '0' COMMENT '电子面单ID',
    `city_mobile`       varchar(255)          DEFAULT NULL,
    `city_info`         longtext,
    `city_name`         varchar(255)          DEFAULT NULL,
    `shop_order_id`     varchar(255)          DEFAULT NULL,
    `status`            int(11) NOT NULL DEFAULT '0',
    `express_type`      varchar(255)          DEFAULT '',
    `city_service_id`   int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `mch_id` (`mch_id`),
    KEY                 `order_id` (`order_id`),
    KEY                 `send_type` (`send_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_detail_express_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_order_detail_express_relation`;
CREATE TABLE `op_order_detail_express_relation`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                 int(11) NOT NULL,
    `mch_id`                  int(11) NOT NULL,
    `order_id`                int(11) NOT NULL,
    `order_detail_id`         int(11) NOT NULL,
    `order_detail_express_id` int(11) NOT NULL,
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `order_detail_id` (`order_detail_id`),
    KEY                       `order_detail_express_id` (`order_detail_express_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_detail_vip_card_info
-- ----------------------------
DROP TABLE IF EXISTS `op_order_detail_vip_card_info`;
CREATE TABLE `op_order_detail_vip_card_info`
(
    `id`                       int(11) NOT NULL AUTO_INCREMENT,
    `vip_card_order_id`        int(11) NOT NULL,
    `order_detail_id`          int(11) NOT NULL,
    `order_detail_total_price` decimal(10, 2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_express_single
-- ----------------------------
DROP TABLE IF EXISTS `op_order_express_single`;
CREATE TABLE `op_order_express_single`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `order_id`        int(11) NOT NULL COMMENT '订单id',
    `express_code`    varchar(255) NOT NULL COMMENT '快递公司编码',
    `ebusiness_id`    varchar(255) NOT NULL COMMENT '快递鸟id',
    `print_teplate`   longtext,
    `order`           longtext     NOT NULL COMMENT '订单信息',
    `is_delete`       tinyint(1) NOT NULL,
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`      timestamp    NOT NULL,
    `order_detail_id` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_pay_result
-- ----------------------------
DROP TABLE IF EXISTS `op_order_pay_result`;
CREATE TABLE `op_order_pay_result`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `order_id`   int(11) NOT NULL,
    `data`       longtext COMMENT 'json数据',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY          `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `op_order_refund`;
CREATE TABLE `op_order_refund`
(
    `id`                       int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                  int(11) NOT NULL,
    `mch_id`                   int(11) NOT NULL DEFAULT '0',
    `user_id`                  int(11) NOT NULL,
    `order_id`                 int(11) NOT NULL,
    `order_detail_id`          int(11) NOT NULL COMMENT '关联订单详情',
    `order_no`                 varchar(100)   NOT NULL DEFAULT '' COMMENT '退款单号',
    `type`                     tinyint(1) NOT NULL COMMENT '售后类型：1=退货退款，2=换货',
    `refund_price`             decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
    `remark`                   varchar(255)   NOT NULL DEFAULT '' COMMENT '用户退款备注、说明',
    `pic_list`                 mediumtext     NOT NULL COMMENT '用户上传图片凭证',
    `status`                   tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.待商家处理 2.同意 3.拒绝',
    `status_time`              timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '商家处理时间',
    `merchant_remark`          varchar(255)   NOT NULL DEFAULT '' COMMENT '商家同意|拒绝备注、理由',
    `is_send`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否发货 0.未发货1.已发货',
    `send_time`                timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发货时间',
    `customer_name`            varchar(65)             DEFAULT '' COMMENT '京东商家编号',
    `express`                  varchar(65)    NOT NULL DEFAULT '' COMMENT '快递公司',
    `express_no`               varchar(255)   NOT NULL DEFAULT '' COMMENT '快递单号',
    `address_id`               int(11) NOT NULL DEFAULT '0' COMMENT '退换货地址ID',
    `is_confirm`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '商家确认操作',
    `confirm_time`             timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '确认时间',
    `merchant_customer_name`   varchar(65)             DEFAULT '' COMMENT '商家京东商家编号',
    `merchant_express`         varchar(65)    NOT NULL DEFAULT '' COMMENT '商家发货快递公司',
    `merchant_express_no`      varchar(255)   NOT NULL DEFAULT '' COMMENT '商家发货快递单号',
    `created_at`               timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`               timestamp      NOT NULL,
    `deleted_at`               timestamp      NOT NULL,
    `is_delete`                tinyint(1) NOT NULL DEFAULT '0',
    `refund_time`              timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_refund`                tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否打款，2代表旧数据',
    `reality_refund_price`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '商家实际退款金额',
    `merchant_express_content` varchar(255)   NOT NULL DEFAULT '' COMMENT '物流内容',
    `mobile`                   varchar(255)   NOT NULL DEFAULT '' COMMENT '联系方式',
    `refund_data`              text           NOT NULL COMMENT '售后数据',
    PRIMARY KEY (`id`),
    KEY                        `mall_id` (`mall_id`),
    KEY                        `mch_id` (`mch_id`),
    KEY                        `user_id` (`user_id`),
    KEY                        `order_id` (`order_id`),
    KEY                        `order_detail_id` (`order_detail_id`),
    KEY                        `order_no` (`order_no`),
    KEY                        `type` (`type`),
    KEY                        `status` (`status`),
    KEY                        `is_send` (`is_send`),
    KEY                        `is_confirm` (`is_confirm`),
    KEY                        `is_refund` (`is_refund`),
    KEY                        `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_order_send_template
-- ----------------------------
DROP TABLE IF EXISTS `op_order_send_template`;
CREATE TABLE `op_order_send_template`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(60)  NOT NULL DEFAULT '' COMMENT '发货单名称',
    `cover_pic`  varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
    `params`     text         NOT NULL COMMENT '模板参数',
    `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认模板0.否|1.是',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_send_template_address
-- ----------------------------
DROP TABLE IF EXISTS `op_order_send_template_address`;
CREATE TABLE `op_order_send_template_address`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL,
    `name`       varchar(60)  NOT NULL DEFAULT '' COMMENT '网点名称',
    `username`   varchar(60)  NOT NULL DEFAULT '' COMMENT '联系人',
    `mobile`     varchar(60)  NOT NULL DEFAULT '' COMMENT '联系电话',
    `code`       varchar(60)  NOT NULL DEFAULT '' COMMENT '网点邮编',
    `address`    varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_submit_result
-- ----------------------------
DROP TABLE IF EXISTS `op_order_submit_result`;
CREATE TABLE `op_order_submit_result`
(
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(32) NOT NULL,
    `data`  longtext,
    PRIMARY KEY (`id`),
    KEY     `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_order_vip_card_info
-- ----------------------------
DROP TABLE IF EXISTS `op_order_vip_card_info`;
CREATE TABLE `op_order_vip_card_info`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `order_id`           int(11) NOT NULL COMMENT '订单ID',
    `vip_card_detail_id` int(11) NOT NULL COMMENT '超级会员卡子卡ID',
    `order_total_price`  decimal(10, 2) NOT NULL COMMENT '超级会员卡优惠后订单的金额',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_payment_order
-- ----------------------------
DROP TABLE IF EXISTS `op_payment_order`;
CREATE TABLE `op_payment_order`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT,
    `payment_order_union_id` int(11) NOT NULL,
    `order_no`               varchar(32)   NOT NULL,
    `amount`                 decimal(9, 2) NOT NULL,
    `is_pay`                 int(1) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
    `pay_type`               int(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付，2=货到付款，3=余额支付，4=支付宝支付',
    `title`                  varchar(128)  NOT NULL,
    `created_at`             timestamp NULL DEFAULT NULL,
    `updated_at`             timestamp NULL DEFAULT NULL,
    `notify_class`           varchar(512)  NOT NULL,
    `refund`                 decimal(9, 2) NOT NULL DEFAULT '0.00' COMMENT '已退款金额',
    PRIMARY KEY (`id`),
    KEY                      `payment_order_union_id` (`payment_order_union_id`),
    KEY                      `order_no` (`order_no`),
    KEY                      `is_pay` (`is_pay`),
    KEY                      `pay_type` (`pay_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_payment_order_union
-- ----------------------------
DROP TABLE IF EXISTS `op_payment_order_union`;
CREATE TABLE `op_payment_order_union`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `user_id`           int(11) NOT NULL DEFAULT '0',
    `order_no`          varchar(32)   NOT NULL,
    `amount`            decimal(9, 2) NOT NULL,
    `is_pay`            int(1) NOT NULL DEFAULT '0' COMMENT '支付状态：0=未支付，1=已支付',
    `pay_type`          int(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付，2=货到付款，3=余额支付，4=支付宝支付',
    `title`             varchar(128)  NOT NULL,
--     `qf_syssn`          varchar(100)           DEFAULT '',
    `support_pay_types` text COMMENT '支持的支付方式（JSON）',
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL,
    `app_version`       varchar(32)   NOT NULL DEFAULT '' COMMENT '小程序端版本',
    `platform`          varchar(32)   NOT NULL DEFAULT '',
    `is_profit_sharing` tinyint(1) DEFAULT 0 COMMENT '1：分账；0：否',
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `user_id` (`user_id`),
    KEY                 `order_no` (`order_no`),
    KEY                 `is_pay` (`is_pay`),
    KEY                 `pay_type` (`pay_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_payment_refund
-- ----------------------------
DROP TABLE IF EXISTS `op_payment_refund`;
CREATE TABLE `op_payment_refund`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `user_id`      int(11) NOT NULL,
    `order_no`     varchar(120)  NOT NULL DEFAULT '' COMMENT '退款单号',
    `amount`       decimal(9, 2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
    `is_pay`       int(1) NOT NULL DEFAULT '0' COMMENT '支付状态 0--未支付|1--已支付',
    `pay_type`     int(1) NOT NULL DEFAULT '0' COMMENT '支付方式：1=微信支付，2=货到付款，3=余额支付，4=支付宝支付',
    `title`        varchar(128)  NOT NULL DEFAULT '',
    `created_at`   timestamp NULL DEFAULT NULL,
    `updated_at`   timestamp NULL DEFAULT NULL,
    `out_trade_no` varchar(255)  NOT NULL DEFAULT '' COMMENT '支付单号',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `order_no` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='退款订单';

-- ----------------------------
-- Table structure for op_payment_transfer
-- ----------------------------
DROP TABLE IF EXISTS `op_payment_transfer`;
CREATE TABLE `op_payment_transfer`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `user_id`           int(11) NOT NULL,
    `order_no`          varchar(255)  NOT NULL COMMENT '提交微信或支付宝的订单号',
    `transfer_order_no` varchar(255)  NOT NULL COMMENT '发起 打款的订单号',
    `amount`            decimal(9, 2) NOT NULL DEFAULT '0.00' COMMENT '金额',
    `is_pay`            int(1) NOT NULL DEFAULT '0' COMMENT '支付状态 0--未支付|1--已支付',
    `pay_type`          varchar(255)  NOT NULL DEFAULT '' COMMENT '方式：wechat--微信打款 alipay--支付宝打款',
    `title`             varchar(128)  NOT NULL DEFAULT '',
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='平台向用户打款';

-- ----------------------------
-- Table structure for op_pay_type
-- ----------------------------
DROP TABLE IF EXISTS `op_pay_type`;
CREATE TABLE `op_pay_type`
(
    `id`                int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `name`              varchar(255)  NOT NULL COMMENT '支付名称',
    `type`              tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:微信  2:支付宝',
    `channel`           tinyint(1) DEFAULT '1' COMMENT '1:官方  2:通华',
    `appid`             varchar(255)  NOT NULL DEFAULT '',
    `mchid`             varchar(32)   NOT NULL DEFAULT '',
    `key`               varchar(32)   NOT NULL DEFAULT '',
--     `qf_code`           varchar(100)           DEFAULT NULL COMMENT '钱方code',
    `currency`          tinyint(1) DEFAULT 1 COMMENT '结算货币；1人民币，2港币，3美元',
    `cert_pem`          varchar(2000) NOT NULL DEFAULT '',
    `key_pem`           varchar(2000) NOT NULL DEFAULT '',
    `is_service`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为服务商支付  0否 1是',
    `service_key`       varchar(32)   NOT NULL DEFAULT '',
    `service_appid`     varchar(255)  NOT NULL DEFAULT '' COMMENT '服务商appid',
    `service_mchid`     varchar(255)  NOT NULL DEFAULT '' COMMENT '服务商mch_id',
    `service_cert_pem`  varchar(2000) NOT NULL DEFAULT '',
    `service_key_pem`   varchar(2000) NOT NULL DEFAULT '',
    `is_auto_add`       tinyint(4) NOT NULL DEFAULT '0' COMMENT '0否 1是',
    `alipay_appid`      varchar(32)   NOT NULL DEFAULT '',
    `app_private_key`   varchar(2000) NOT NULL DEFAULT '' COMMENT '支付宝应用私钥',
    `alipay_public_key` text COMMENT '支付宝平台公钥',
    `appcert`           text,
    `alipay_rootcert`   text COMMENT '支付宝根证书',
    `tl_appid`          varchar(20)            DEFAULT NULL COMMENT '通联appID',
    `tl_merchantId`     varchar(35)            DEFAULT NULL COMMENT '通联商户号',
    `tl_rsaPrivateKey`  text COMMENT '通联RSA私钥',
    `tl_rsaPublicKey`   text COMMENT '通联RSA公钥',
    `is_delete`         tinyint(1) NOT NULL DEFAULT '0',
    `updated_at`        timestamp     NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `created_at`        timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`        timestamp     NOT NULL,
    `v3key`             varchar(255)           DEFAULT NULL COMMENT '微信支付V3密钥',
    `is_v3`             tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现是否使用v3  1使用老版本  2使用v3',
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_pc_banner
-- ----------------------------
DROP TABLE IF EXISTS `op_pc_banner`;
CREATE TABLE `op_pc_banner`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `title`      varchar(100)          DEFAULT '' COMMENT '标题',
    `pic_url`    varchar(300) NOT NULL COMMENT '图片',
    `page_url`   varchar(300)          DEFAULT '' COMMENT '页面路径',
    `sort`       int(1) DEFAULT '1' COMMENT '排序',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `deleted_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '删除时间',
    `updated_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pc_nav
-- ----------------------------
DROP TABLE IF EXISTS `op_pc_nav`;
CREATE TABLE `op_pc_nav`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(50)  NOT NULL DEFAULT '' COMMENT '导航名称',
    `url`        varchar(350) NOT NULL DEFAULT '' COMMENT '导航链接',
    `open_type`  char(1)               DEFAULT '1' COMMENT '打开方式；1本页，2新页',
    `sort`       int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0.隐藏|1.显示',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `is_delete` (`is_delete`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pc_user_login
-- ----------------------------
DROP TABLE IF EXISTS `op_pc_user_login`;
CREATE TABLE `op_pc_user_login`
(
    `id`          int(20) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(20) NOT NULL COMMENT '用户ID',
    `token`       varchar(32)        DEFAULT NULL COMMENT '登录TOKEN',
    `ip`          varchar(16)        DEFAULT NULL COMMENT '登录IP',
    `expire_time` char(10)  NOT NULL COMMENT '有效时间戳',
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`  timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
    PRIMARY KEY (`id`),
    KEY           `user_id` (`user_id`) USING BTREE,
    KEY           `token` (`token`) USING BTREE,
    KEY           `mall_id` (`mall_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录记录';

-- ----------------------------
-- Table structure for op_pc_user_register
-- ----------------------------
DROP TABLE IF EXISTS `op_pc_user_register`;
CREATE TABLE `op_pc_user_register`
(
    `id`          int(20) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `token`       varchar(32)        DEFAULT NULL COMMENT 'TOKEN',
    `data`        text COMMENT '数据',
    `status`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0.进行中|1.完成',
    `expire_time` char(10)  NOT NULL COMMENT '有效时间戳',
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY           `token` (`token`) USING BTREE,
    KEY           `mall_id` (`mall_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='注册临时数据表';

-- ----------------------------
-- Table structure for op_pick_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_pick_activity`;
CREATE TABLE `op_pick_activity`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `status`        int(11) NOT NULL DEFAULT '0' COMMENT '状态 0下架 1上架',
    `is_delete`     tinyint(4) NOT NULL DEFAULT '0',
    `created_at`    timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    timestamp      NOT NULL,
    `deleted_at`    timestamp      NOT NULL,
    `title`         varchar(255)   NOT NULL DEFAULT '' COMMENT '活动标题',
    `start_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始时间',
    `end_at`        timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动结束时间',
    `rule_price`    decimal(10, 2) NOT NULL COMMENT '组合方案 元',
    `rule_num`      int(11) NOT NULL COMMENT '组合方案 件',
    `is_area_limit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单独区域购买',
    `area_limit`    longtext       NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY             `idx_1` (`mall_id`,`is_delete`,`created_at`),
    KEY             `sort` (`start_at`,`end_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选活动';

-- ----------------------------
-- Table structure for op_pick_cart
-- ----------------------------
DROP TABLE IF EXISTS `op_pick_cart`;
CREATE TABLE `op_pick_cart`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `user_id`          int(11) NOT NULL,
    `goods_id`         int(11) NOT NULL COMMENT '商品',
    `attr_id`          int(11) NOT NULL COMMENT '商品规格',
    `num`              int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`       timestamp NOT NULL,
    `updated_at`       timestamp NOT NULL,
    `attr_info`        text,
    `pick_activity_id` int(11) NOT NULL COMMENT '活动id',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pick_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_pick_goods`;
CREATE TABLE `op_pick_goods`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `status`           tinyint(1) NOT NULL COMMENT '状态 0 关闭 1开启',
    `goods_id`         int(11) NOT NULL DEFAULT '0',
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp NOT NULL,
    `deleted_at`       timestamp NOT NULL,
    `pick_activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
    `stock`            int(11) NOT NULL COMMENT '总库存',
    `sort`             int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                `activity` (`pick_activity_id`) USING BTREE,
    KEY                `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选商品';

-- ----------------------------
-- Table structure for op_pick_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_pick_setting`;
CREATE TABLE `op_pick_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      text         NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp    NOT NULL COMMENT '更新时间',
    `is_delete`  int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',
    `deleted_at` timestamp    NOT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='N元任选设置';

-- ----------------------------
-- Table structure for op_pintuan_banners
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_banners`;
CREATE TABLE `op_pintuan_banners`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_cats
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_cats`;
CREATE TABLE `op_pintuan_cats`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `cat_id`     int(11) NOT NULL,
    `sort`       int(11) NOT NULL DEFAULT '100',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_goods`;
CREATE TABLE `op_pintuan_goods`
(
    `id`                  int(11) unsigned NOT NULL AUTO_INCREMENT,
    `is_alone_buy`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许单独购买',
    `mall_id`             int(11) NOT NULL,
    `goods_id`            int(11) NOT NULL,
    `end_time`            timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '拼团结束时间',
    `groups_restrictions` int(11) NOT NULL DEFAULT '-1' COMMENT '拼团次数限制',
    `is_delete`           tinyint(4) NOT NULL DEFAULT '0',
    `is_sell_well`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热销',
    `start_time`          timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '活动开始日期',
    `is_auto_add_robot`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动添加机器人0.否|1.是',
    `add_robot_time`      int(11) NOT NULL DEFAULT '0' COMMENT '机器人参与时间0.表示不添加',
    `pintuan_goods_id`    int(11) NOT NULL DEFAULT '0' COMMENT '是否为同一组',
    PRIMARY KEY (`id`),
    KEY                   `mall_id` (`mall_id`),
    KEY                   `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_goods_attr`;
CREATE TABLE `op_pintuan_goods_attr`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `pintuan_price`           decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '拼团价',
    `pintuan_stock`           int(11) NOT NULL COMMENT '拼团库存',
    `pintuan_goods_groups_id` int(11) NOT NULL COMMENT '拼团设置ID',
    `goods_attr_id`           int(11) NOT NULL COMMENT '商城商品规格ID',
    `goods_id`                int(11) NOT NULL COMMENT '商城商品ID',
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `goods_id` (`goods_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_goods_groups
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_goods_groups`;
CREATE TABLE `op_pintuan_goods_groups`
(
    `id`                 int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`           int(11) NOT NULL,
    `people_num`         int(11) NOT NULL DEFAULT '2' COMMENT '拼团人数',
    `preferential_price` decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '团长优惠',
    `pintuan_time`       int(11) NOT NULL DEFAULT '1' COMMENT '拼团限间',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `group_num`          int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `goods_id` (`goods_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_goods_member_price
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_goods_member_price`;
CREATE TABLE `op_pintuan_goods_member_price`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `level`                   int(11) NOT NULL,
    `price`                   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `goods_id`                int(11) NOT NULL COMMENT '商城商品ID',
    `goods_attr_id`           int(11) NOT NULL COMMENT '商城商品规格ID',
    `pintuan_goods_groups_id` int(11) NOT NULL COMMENT '拼团设置ID',
    `pintuan_goods_attr_id`   int(11) NOT NULL COMMENT '拼团商品规格ID',
    `is_delete`               tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_goods_share
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_goods_share`;
CREATE TABLE `op_pintuan_goods_share`
(
    `id`                      int(11) NOT NULL AUTO_INCREMENT,
    `share_commission_first`  decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金比例',
    `share_commission_second` decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金比例',
    `share_commission_third`  decimal(10, 2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金比例',
    `goods_id`                int(11) NOT NULL,
    `goods_attr_id`           int(11) NOT NULL COMMENT '商城商品规格ID',
    `pintuan_goods_groups_id` int(11) NOT NULL COMMENT '拼团设置ID',
    `pintuan_goods_attr_id`   int(11) NOT NULL DEFAULT '0' COMMENT '拼团商品规格ID',
    `is_delete`               tinyint(4) NOT NULL DEFAULT '0',
    `level`                   int(11) NOT NULL DEFAULT '0' COMMENT '分销商等级',
    PRIMARY KEY (`id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_orders`;
CREATE TABLE `op_pintuan_orders`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `preferential_price`      decimal(10, 2) NOT NULL COMMENT '团长优惠',
    `mall_id`                 int(11) NOT NULL,
    `success_time`            timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '成团时间',
    `status`                  tinyint(4) NOT NULL DEFAULT '0' COMMENT '0.待付款|1.拼团中|2.拼团成功|3.拼团失败',
    `people_num`              int(11) NOT NULL COMMENT '成团所需人数',
    `pintuan_time`            int(11) NOT NULL DEFAULT '2' COMMENT '拼团限时(小时)',
    `pintuan_goods_groups_id` int(11) NOT NULL COMMENT '阶梯团ID',
    `goods_id`                int(11) NOT NULL COMMENT '商品ID',
    `created_at`              timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`              timestamp      NOT NULL,
    `expected_over_time`      int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `index_name` (`expected_over_time`),
    KEY                       `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_order_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_order_relation`;
CREATE TABLE `op_pintuan_order_relation`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id`         int(11) NOT NULL COMMENT '商城订单ID',
    `user_id`          int(11) NOT NULL,
    `pintuan_order_id` int(11) NOT NULL COMMENT '组团订单ID',
    `is_parent`        tinyint(11) NOT NULL DEFAULT '0' COMMENT '是否为团长',
    `is_groups`        tinyint(4) NOT NULL COMMENT '0.单独购买|1.拼团购买',
    `created_at`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    `robot_id`         int(11) NOT NULL DEFAULT '0',
    `cancel_status`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '拼团订单取消状态:0.未取消|1.超出拼团总人数取消',
    PRIMARY KEY (`id`),
    KEY                `order_id` (`order_id`),
    KEY                `pintuan_order_id` (`pintuan_order_id`),
    KEY                `user_id` (`user_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_robots
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_robots`;
CREATE TABLE `op_pintuan_robots`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `nickname`   varchar(65)  NOT NULL DEFAULT '' COMMENT '机器人昵称',
    `avatar`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pintuan_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_pintuan_setting`;
CREATE TABLE `op_pintuan_setting`
(
    `id`                        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `is_share`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否短信提醒',
    `is_mail`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启邮件通知',
    `is_print`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启订单打印',
    `rules`                     text      NOT NULL COMMENT '拼团规则',
    `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '区域购买限制',
    `advertisement`             text      NOT NULL COMMENT '拼团广告',
    `is_advertisement`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启拼团广告',
    `created_at`                timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    `payment_type`              longtext  NOT NULL COMMENT '支付方式',
    `send_type`                 longtext  NOT NULL COMMENT '发货方式',
    `goods_poster`              longtext  NOT NULL COMMENT '自定义海报',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='砍价设置';

-- ----------------------------
-- Table structure for op_plugin_cat
-- ----------------------------
DROP TABLE IF EXISTS `op_plugin_cat`;
CREATE TABLE `op_plugin_cat`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `name`         varchar(24)  NOT NULL,
    `display_name` varchar(255) NOT NULL,
    `color`        varchar(24)  NOT NULL DEFAULT '',
    `sort`         int(11) NOT NULL DEFAULT '100',
    `icon`         text,
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `add_time`     datetime              DEFAULT NULL,
    `update_time`  datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY            `name` (`name`),
    KEY            `sort` (`sort`),
    KEY            `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of op_plugin_cat   @czs 更换插件图标和背景颜色
-- ----------------------------
INSERT INTO `op_plugin_cat`
VALUES ('1', 'xb4z5hqs6388pd5c', '销售渠道', '#1258cc', '100', null, '0', null, null);
INSERT INTO `op_plugin_cat`
VALUES ('2', 'nddsdjdaxzmmeqk4', '促销玩法', '#fd8d08', '100', null, '0', null, null);
INSERT INTO `op_plugin_cat`
VALUES ('3', 'xwmgpax7jkzjrxha', '获客工具', '#d63d35', '100', null, '0', null, null);
INSERT INTO `op_plugin_cat`
VALUES ('4', '3wsern27hxspzytd', '客户维护', '#8431f6', '100', null, '0', null, null);
INSERT INTO `op_plugin_cat`
VALUES ('5', 'kwfhnndnbakznksb', '常用工具', '#00d8a0', '100', null, '0', null, null);

-- ----------------------------
-- Table structure for op_plugin_cat_rel
-- ----------------------------
DROP TABLE IF EXISTS `op_plugin_cat_rel`;
CREATE TABLE `op_plugin_cat_rel`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `plugin_name`     varchar(32) NOT NULL,
    `plugin_cat_name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`),
    KEY               `plugin_name` (`plugin_name`),
    KEY               `plugin_cat_name` (`plugin_cat_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of op_plugin_cat_rel
-- ----------------------------
INSERT INTO `op_plugin_cat_rel`
VALUES ('1', 'wxapp', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('2', 'aliapp', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('3', 'ttapp', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('4', 'bdapp', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('5', 'advance', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('6', 'composition', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('7', 'pick', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('8', 'booking', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('9', 'bargain', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('10', 'gift', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('11', 'miaosha', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('12', 'pintuan', 'nddsdjdaxzmmeqk4');
-- INSERT INTO `op_plugin_cat_rel` VALUES ('13', 'shopping', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('14', 'ecard', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('15', 'flash_sale', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('16', 'pond', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('17', 'bonus', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('18', 'check_in', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('19', 'fxhb', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('20', 'lottery', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('21', 'mch', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('22', 'quick_share', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('23', 'scratch', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('24', 'step', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('25', 'stock', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('26', 'region', 'xwmgpax7jkzjrxha');
INSERT INTO `op_plugin_cat_rel`
VALUES ('27', 'integral_mall', '3wsern27hxspzytd');
INSERT INTO `op_plugin_cat_rel`
VALUES ('28', 'vip_card', '3wsern27hxspzytd');
INSERT INTO `op_plugin_cat_rel`
VALUES ('29', 'assistant', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('30', 'diy', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('31', 'app_admin', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('32', 'clerk', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('33', 'dianqilai', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('34', 'scan_code_pay', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('35', 'community', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('36', 'exchange', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('37', 'wholesale', 'nddsdjdaxzmmeqk4');
INSERT INTO `op_plugin_cat_rel`
VALUES ('38', 'wechat', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('39', 'mobile', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('40', 'teller', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('41', 'invoice', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('42', 'app', 'xb4z5hqs6388pd5c');
INSERT INTO `op_plugin_cat_rel`
VALUES ('43', 'minishop', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('44', 'scrm', '3wsern27hxspzytd');
INSERT INTO `op_plugin_cat_rel`
VALUES ('45', 'url_scheme', 'kwfhnndnbakznksb');
INSERT INTO `op_plugin_cat_rel`
VALUES ('46', 'erp', 'kwfhnndnbakznksb');

-- ----------------------------
-- Table structure for op_pond
-- ----------------------------
DROP TABLE IF EXISTS `op_pond`;
CREATE TABLE `op_pond`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '别名',
    `type`       int(11) NOT NULL COMMENT '1.红包2.优惠卷3.积分4.实物.5.无',
    `goods_id`   int(11) NOT NULL DEFAULT '0' COMMENT '商品',
    `num`        int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
    `price`      decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '红包价格',
    `image_url`  varchar(255)                    NOT NULL DEFAULT '' COMMENT '图片',
    `coupon_id`  int(11) NOT NULL DEFAULT '0' COMMENT '优惠卷',
    `stock`      int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `created_at` timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp                       NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for op_pond_log
-- ----------------------------
DROP TABLE IF EXISTS `op_pond_log`;
CREATE TABLE `op_pond_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `pond_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `status`     int(11) NOT NULL COMMENT ' 0未领取1 已领取',
    `type`       int(11) NOT NULL COMMENT '1.红包2.优惠卷3.积分4.实物5无',
    `num`        int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
    `detail`     varchar(2000)  NOT NULL DEFAULT '' COMMENT '优惠券信息',
    `goods_id`   int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
    `price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '价格',
    `order_id`   int(11) NOT NULL DEFAULT '0',
    `raffled_at` timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `token`      varchar(255)   NOT NULL DEFAULT '' COMMENT '订单表token',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `pond_id` (`pond_id`),
    KEY          `user_id` (`user_id`),
    KEY          `status` (`status`),
    KEY          `type` (`type`),
    KEY          `goods_id` (`goods_id`),
    KEY          `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for op_pond_log_coupon_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_pond_log_coupon_relation`;
CREATE TABLE `op_pond_log_coupon_relation`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL COMMENT '用户优惠券id',
    `pond_log_id`    int(11) NOT NULL COMMENT '奖品记录id',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`     timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pond_order
-- ----------------------------
DROP TABLE IF EXISTS `op_pond_order`;
CREATE TABLE `op_pond_order`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `pond_log_id` int(11) NOT NULL,
    `order_id`    int(11) NOT NULL,
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at`  timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY           `order_id` (`order_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_pond_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_pond_setting`;
CREATE TABLE `op_pond_setting`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`              int(11) NOT NULL,
    `title`                varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题',
    `type`                 smallint(1) NOT NULL COMMENT '1.天 2 用户',
    `probability`          int(11) NOT NULL DEFAULT '0' COMMENT '概率',
    `oppty`                int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数',
    `start_at`             timestamp    NOT NULL COMMENT '开始时间',
    `end_at`               timestamp    NOT NULL COMMENT '结束时间',
    `deplete_integral_num` int(11) NOT NULL DEFAULT '0' COMMENT '消耗积分',
    `rule`                 longtext     NOT NULL COMMENT '规则',
    `created_at`           timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`           timestamp    NOT NULL,
    `payment_type`         longtext     NOT NULL COMMENT '支付方式',
    `send_type`            longtext     NOT NULL COMMENT '发货方式',
    `is_sms`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
    `is_mail`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启邮件提醒',
    `is_print`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启打印',
    `bg_pic`               varchar(255) NOT NULL DEFAULT '' COMMENT '背景图',
    `bg_color`             varchar(255) NOT NULL DEFAULT '' COMMENT '背景颜色',
    `bg_color_type`        varchar(255) NOT NULL DEFAULT '' COMMENT '背景颜色类型',
    `bg_gradient_color`    varchar(255) NOT NULL DEFAULT '' COMMENT '背景渐变颜色',
    PRIMARY KEY (`id`),
    KEY                    `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_postage_rules
-- ----------------------------
DROP TABLE IF EXISTS `op_postage_rules`;
CREATE TABLE `op_postage_rules`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `name`       varchar(65) NOT NULL DEFAULT '',
    `detail`     longtext    NOT NULL COMMENT '规则详情',
    `status`     tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否默认',
    `type`       tinyint(4) NOT NULL DEFAULT '1' COMMENT '计费方式【1=>按重计费、2=>按件计费】',
    `created_at` timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp   NOT NULL,
    `deleted_at` timestamp   NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_printer
-- ----------------------------
DROP TABLE IF EXISTS `op_printer`;
CREATE TABLE `op_printer`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `type`       varchar(255) NOT NULL COMMENT '类型',
    `name`       varchar(255) NOT NULL COMMENT '名称',
    `setting`    longtext     NOT NULL COMMENT '设置',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_printer_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_printer_setting`;
CREATE TABLE `op_printer_setting`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `mch_id`          int(11) NOT NULL DEFAULT '0',
    `printer_id`      int(11) NOT NULL COMMENT '打印机id',
    `block_id`        int(11) NOT NULL DEFAULT '0' COMMENT '模板id',
    `status`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1启用',
    `is_attr`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不使用规格 1使用规格',
    `type`            longtext     NOT NULL COMMENT 'order(下单打印)-> 0关闭 1开启 \r\npay (付款打印)-> 0关闭 1开启 \r\nconfirm (确认收货打印)-> 0关闭 1开启 \r\n ',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp    NOT NULL,
    `deleted_at`      timestamp    NOT NULL,
    `big`             int(11) NOT NULL DEFAULT '0' COMMENT '放大倍数',
    `show_type`       longtext     NOT NULL COMMENT '打印参数 attr 规格 goods_no 货号 form_data 下单表单',
    `order_send_type` varchar(255) NOT NULL DEFAULT '',
    `store_id`        int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `mch_id` (`mch_id`),
    KEY               `status` (`status`),
    KEY               `store_id` (`store_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_qr_code_parameter
-- ----------------------------
DROP TABLE IF EXISTS `op_qr_code_parameter`;
CREATE TABLE `op_qr_code_parameter`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `token`      varchar(30)  NOT NULL DEFAULT '',
    `data`       mediumtext   NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `path`       varchar(255) NOT NULL DEFAULT '' COMMENT '小程序路径',
    `use_number` int(11) NOT NULL DEFAULT '0' COMMENT '使用次数',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_quick_share_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_quick_share_goods`;
CREATE TABLE `op_quick_share_goods`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `goods_id`           int(11) NOT NULL DEFAULT '0',
    `status`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
    `share_text`         varchar(255) NOT NULL COMMENT '分享文本',
    `share_pic`          longtext     NOT NULL COMMENT '素材图片',
    `material_sort`      int(11) NOT NULL DEFAULT '0' COMMENT '素材排序',
    `is_top`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
    `material_video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '动态视频',
    `material_cover_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频封面',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`         timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp    NOT NULL,
    `deleted_at`         timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY                  `goods_id` (`goods_id`) USING BTREE,
    KEY                  `mall_id` (`mall_id`),
    KEY                  `status` (`status`),
    KEY                  `is_top` (`is_top`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_quick_share_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_quick_share_setting`;
CREATE TABLE `op_quick_share_setting`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `type`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '发圈对象 仅素材 1全部商品',
    `goods_poster` longtext  NOT NULL,
    `created_at`   timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_quick_shop_cats
-- ----------------------------
DROP TABLE IF EXISTS `op_quick_shop_cats`;
CREATE TABLE `op_quick_shop_cats`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `cat_id`     int(11) NOT NULL,
    `sort`       int(11) NOT NULL DEFAULT '0' COMMENT '排序',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_recharge
-- ----------------------------
DROP TABLE IF EXISTS `op_recharge`;
CREATE TABLE `op_recharge`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `name`           varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '名称',
    `pay_price`      decimal(10, 2)                  NOT NULL COMMENT '支付价格',
    `send_price`     decimal(10, 2)                  NOT NULL DEFAULT '0.00' COMMENT '赠送价格',
    `is_delete`      smallint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp                       NOT NULL,
    `deleted_at`     timestamp                       NOT NULL,
    `send_integral`  int(11) NOT NULL DEFAULT '0' COMMENT '赠送的积分',
    `send_member_id` int(11) NOT NULL DEFAULT '0' COMMENT '赠送的会员',
    `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型',
    `send_card` longtext NULL COMMENT '赠送卡券',
    `send_coupon` longtext NULL COMMENT '赠送优惠券',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_recharge_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_recharge_orders`;
CREATE TABLE `op_recharge_orders`
(
    `id`             int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `order_no`       varchar(32)    NOT NULL DEFAULT '',
    `user_id`        int(11) NOT NULL,
    `pay_price`      decimal(10, 2) NOT NULL COMMENT '充值金额',
    `send_price`     decimal(10, 2) NOT NULL COMMENT '赠送金额',
    `pay_type`       tinyint(4) NOT NULL COMMENT '支付方式 1.线上支付',
    `is_pay`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付 0--未支付 1--支付',
    `pay_time`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`      tinyint(4) NOT NULL DEFAULT '0',
    `created_at`     timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp      NOT NULL,
    `deleted_at`     timestamp      NOT NULL,
    `send_integral`  int(11) NOT NULL DEFAULT '0' COMMENT '赠送的积分',
    `send_member_id` int(11) NOT NULL DEFAULT '0' COMMENT '赠送的会员',
    `send_type` int(10) NOT NULL DEFAULT 7 COMMENT '赠送类型',
    `send_card` longtext NULL COMMENT '赠送卡券',
    `send_coupon` longtext NULL COMMENT '赠送优惠券',
    PRIMARY KEY (`id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_refund_address
-- ----------------------------
DROP TABLE IF EXISTS `op_refund_address`;
CREATE TABLE `op_refund_address`
(
    `id`             int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `mch_id`         int(11) NOT NULL DEFAULT '0',
    `name`           varchar(65)  NOT NULL DEFAULT '',
    `address`        varchar(255) NOT NULL DEFAULT '',
    `address_detail` varchar(255) NOT NULL DEFAULT '',
    `mobile`         varchar(255) NOT NULL DEFAULT '',
    `remark`         varchar(255) NOT NULL DEFAULT '',
    `created_at`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp    NOT NULL,
    `deleted_at`     timestamp    NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_region_area
-- ----------------------------
DROP TABLE IF EXISTS `op_region_area`;
CREATE TABLE `op_region_area`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `name`               varchar(100)   NOT NULL DEFAULT '' COMMENT '区域名称',
    `province_rate`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '省代理分红比例',
    `city_rate`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '市代理分红比例',
    `district_rate`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '区/县分红比例',
    `province_condition` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '省代理条件',
    `city_condition`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '市代理条件',
    `district_condition` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '区/县代理条件',
    `become_type`        tinyint(2) NOT NULL DEFAULT '0' COMMENT '1:下线总人数\r\n2:分销订单总数\r\n3:分销订单总金额\r\n4:累计佣金总额\r\n5:已提现佣金总额\r\n6:消费金额',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `created_at`         timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp      NOT NULL,
    `deleted_at`         timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY                  `index_1` (`mall_id`,`is_delete`,`created_at`),
    KEY                  `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='区域区域表';

-- ----------------------------
-- Table structure for op_region_area_detail
-- ----------------------------
DROP TABLE IF EXISTS `op_region_area_detail`;
CREATE TABLE `op_region_area_detail`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `area_id`     int(11) NOT NULL COMMENT '区域id',
    `province_id` int(2) NOT NULL COMMENT '省id',
    `is_delete`   tinyint(2) NOT NULL DEFAULT '0',
    `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `area_id` (`area_id`),
    KEY           `index_1` (`mall_id`,`area_id`,`is_delete`),
    KEY           `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理区域详情表';

-- ----------------------------
-- Table structure for op_region_bonus_log
-- ----------------------------
DROP TABLE IF EXISTS `op_region_bonus_log`;
CREATE TABLE `op_region_bonus_log`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL DEFAULT '0',
    `bonus_type`      tinyint(4) NOT NULL DEFAULT '0' COMMENT '1按周，2按月',
    `pre_bonus_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '预计分红金额',
    `bonus_price`     decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '分红金额',
    `bonus_rate`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '当时的分红比例',
    `pre_order_num`   int(11) NOT NULL DEFAULT '0' COMMENT '预计分红订单数',
    `order_num`       int(11) NOT NULL DEFAULT '0' COMMENT '分红订单数',
    `region_num`      int(11) NOT NULL DEFAULT '0' COMMENT '当时区域人数',
    `start_time`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-开始时间',
    `end_time`        timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-结束时间',
    `created_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_region_cash
-- ----------------------------
DROP TABLE IF EXISTS `op_region_cash`;
CREATE TABLE `op_region_cash`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `order_no`       varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
    `service_charge` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',
    `type`           varchar(255)   NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
    `extra`          longtext       NOT NULL COMMENT '额外信息 例如微信账号、支付宝账号等',
    `status`         int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',
    `is_delete`      int(11) NOT NULL DEFAULT '0',
    `created_at`     datetime       NOT NULL,
    `updated_at`     datetime       NOT NULL,
    `deleted_at`     datetime       NOT NULL,
    `content`        longtext,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='提现记录表';

-- ----------------------------
-- Table structure for op_region_cash_log
-- ----------------------------
DROP TABLE IF EXISTS `op_region_cash_log`;
CREATE TABLE `op_region_cash_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        int(11) NOT NULL DEFAULT '1' COMMENT '类型 1--收入 2--支出',
    `price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '变动佣金',
    `desc`        longtext,
    `custom_desc` longtext,
    `level_id`    int(11) NOT NULL DEFAULT '0' COMMENT '当时的区域等级',
    `level_name`  varchar(100)   NOT NULL DEFAULT '',
    `order_num`   int(11) NOT NULL DEFAULT '0',
    `bonus_rate`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '当时的分红比例',
    `bonus_id`    int(11) NOT NULL DEFAULT '0' COMMENT '区域完成分红记录ID',
    `is_delete`   int(11) NOT NULL DEFAULT '0',
    `created_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `province_id` int(11) NOT NULL DEFAULT '0',
    `city_id`     int(11) NOT NULL DEFAULT '0',
    `district_id` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY           `idx_1` (`mall_id`,`is_delete`,`province_id`,`level_id`,`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分红日志';

-- ----------------------------
-- Table structure for op_region_level_up
-- ----------------------------
DROP TABLE IF EXISTS `op_region_level_up`;
CREATE TABLE `op_region_level_up`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:申请升级中  1:通过升级 2:拒绝升级',
    `level`      tinyint(1) NOT NULL COMMENT '升级的等级',
    `reason`     varchar(512) NOT NULL DEFAULT '' COMMENT '理由',
    `is_read`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未读  1已读',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY          `idx_1` (`mall_id`,`is_delete`,`user_id`,`created_at`),
    KEY          `idx_2` (`user_id`),
    KEY          `idx_3` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理升级申请表';

-- ----------------------------
-- Table structure for op_region_order
-- ----------------------------
DROP TABLE IF EXISTS `op_region_order`;
CREATE TABLE `op_region_order`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL DEFAULT '0',
    `order_id`        int(11) NOT NULL DEFAULT '0',
    `total_pay_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '订单实付金额',
    `is_bonus`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已分红，0未分红',
    `bonus_rate`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '分红比例',
    `bonus_time`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间',
    `bonus_id`        int(11) NOT NULL DEFAULT '0' COMMENT '区域完成分红记录ID',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `province`        varchar(20)    NOT NULL COMMENT '省',
    `city`            varchar(20)    NOT NULL COMMENT '市',
    `district`        varchar(20)    NOT NULL COMMENT '区',
    `province_id`     int(11) NOT NULL DEFAULT '0',
    `city_id`         int(11) NOT NULL DEFAULT '0',
    `district_id`     int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY               `order_id` (`order_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分红池';

-- ----------------------------
-- Table structure for op_region_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_region_relation`;
CREATE TABLE `op_region_relation`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL COMMENT '代理id',
    `district_id` int(11) NOT NULL COMMENT '代理的省市区id',
    `is_update`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是升级中的关联地区0：否 1：是',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`,`district_id`,`is_update`,`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理 --- 地区关联表';

-- ----------------------------
-- Table structure for op_region_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_region_setting`;
CREATE TABLE `op_region_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      text         NOT NULL,
    `created_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `is_delete`  int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',
    `deleted_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='区域分红设置';

-- ----------------------------
-- Table structure for op_region_user
-- ----------------------------
DROP TABLE IF EXISTS `op_region_user`;
CREATE TABLE `op_region_user`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `area_id`     int(11) NOT NULL COMMENT '区域ID',
    `province_id` int(11) NOT NULL COMMENT '所属省',
    `level`       tinyint(2) NOT NULL COMMENT '1:省代理  2:市代理 3:区代理',
    `status`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '-2被拒或移除后再次申请没提交 -1移除 0审核中，1同意，2拒绝',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `applyed_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '申请时间',
    `agreed_at`   timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '审核时间',
    PRIMARY KEY (`id`),
    KEY           `created_at` (`created_at`),
    KEY           `idx_1` (`mall_id`,`is_delete`,`status`,`created_at`) USING BTREE,
    KEY           `idx_2` (`mall_id`,`is_delete`,`user_id`,`status`,`created_at`),
    KEY           `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理表';

-- ----------------------------
-- Table structure for op_region_user_info
-- ----------------------------
DROP TABLE IF EXISTS `op_region_user_info`;
CREATE TABLE `op_region_user_info`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `user_id`     int(11) NOT NULL,
    `name`        varchar(100)   NOT NULL DEFAULT '' COMMENT '区域姓名',
    `phone`       varchar(11)    NOT NULL DEFAULT '' COMMENT '区域手机号',
    `all_bonus`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '累计分红',
    `total_bonus` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '当前分红',
    `out_bonus`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '已提现分红',
    `remark`      varchar(200)   NOT NULL DEFAULT '' COMMENT '备注',
    `reason`      text           NOT NULL COMMENT '拒绝理由',
    `created_at`  timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `idx_1` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='代理信息表';

-- ----------------------------
-- Table structure for op_scan_code_pay_activities
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities`;
CREATE TABLE `op_scan_code_pay_activities`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称',
    `start_time` timestamp    NOT NULL COMMENT '活动开始时间',
    `end_time`   timestamp    NOT NULL COMMENT '活动结束时间',
    `send_type`  tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.赠送所有规则|2.赠送满足最高规则',
    `rules`      text COMMENT '买单规则',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_activities_groups
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities_groups`;
CREATE TABLE `op_scan_code_pay_activities_groups`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL DEFAULT '',
    `activity_id` int(11) NOT NULL,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_activities_groups_level
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities_groups_level`;
CREATE TABLE `op_scan_code_pay_activities_groups_level`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `group_id`  int(11) NOT NULL,
    `level`     int(11) NOT NULL,
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_activities_groups_rules
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities_groups_rules`;
CREATE TABLE `op_scan_code_pay_activities_groups_rules`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `group_id`                int(11) NOT NULL,
    `rules_type`              tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.赠送规则|2.优惠规则',
    `consume_money`           decimal(10, 2) NOT NULL COMMENT '单次消费金额',
    `send_integral_num`       int(11) NOT NULL COMMENT '赠送积分',
    `send_integral_type`      tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.固定值|2.百分比',
    `send_money`              decimal(10, 2) NOT NULL COMMENT '赠送余额',
    `send_money_type`         tinyint(1) NOT NULL DEFAULT '1' COMMENT '赠送余额类型1.固定2.百分比',
    `preferential_money`      decimal(10, 2) NOT NULL COMMENT '优惠金额',
    `integral_deduction`      int(11) NOT NULL COMMENT '积分抵扣',
    `integral_deduction_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.固定值|2.百分比',
    `is_coupon`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可使用优惠券',
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `group_id` (`group_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_activities_groups_rules_cards
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities_groups_rules_cards`;
CREATE TABLE `op_scan_code_pay_activities_groups_rules_cards`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `group_rule_id` int(11) NOT NULL,
    `card_id`       int(11) NOT NULL,
    `send_num`      int(11) NOT NULL COMMENT '赠送数量',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_activities_groups_rules_coupons
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_activities_groups_rules_coupons`;
CREATE TABLE `op_scan_code_pay_activities_groups_rules_coupons`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `group_rule_id` int(11) NOT NULL,
    `coupon_id`     int(11) NOT NULL,
    `send_num`      int(11) NOT NULL COMMENT '赠送数量',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_orders`;
CREATE TABLE `op_scan_code_pay_orders`
(
    `id`                          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id`                    int(11) NOT NULL,
    `activity_preferential_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '活动优惠价格',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scan_code_pay_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_scan_code_pay_setting`;
CREATE TABLE `op_scan_code_pay_setting`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                 int(11) NOT NULL,
    `is_scan_code_pay`        tinyint(1) NOT NULL DEFAULT '0',
    `payment_type`            text           NOT NULL,
    `is_share`                tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                  tinyint(1) NOT NULL DEFAULT '0',
    `is_mail`                 tinyint(1) NOT NULL DEFAULT '0',
    `share_type`              tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.百分比|2.固定金额',
    `share_commission_first`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `share_commission_second` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `share_commission_third`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `poster`                  mediumtext     NOT NULL COMMENT '自定义海报',
    `created_at`              timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`              timestamp      NOT NULL,
    `deleted_at`              timestamp      NOT NULL,
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                       `mall_id` (`mall_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scratch
-- ----------------------------
DROP TABLE IF EXISTS `op_scratch`;
CREATE TABLE `op_scratch`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `type`       int(11) NOT NULL COMMENT '1.红包2.优惠卷3.积分4.实物.5.无',
    `status`     tinyint(1) NOT NULL COMMENT '状态 0 关闭 1开启',
    `goods_id`   int(11) NOT NULL DEFAULT '0' COMMENT '商品',
    `num`        int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
    `price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '红包价格',
    `coupon_id`  int(11) NOT NULL DEFAULT '0' COMMENT '优惠券',
    `stock`      int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scratch_log
-- ----------------------------
DROP TABLE IF EXISTS `op_scratch_log`;
CREATE TABLE `op_scratch_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `scratch_id` int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `status`     int(11) NOT NULL DEFAULT '0' COMMENT ' 0预领取 1 未领取 2 已领取',
    `type`       int(11) NOT NULL DEFAULT '0' COMMENT '1.红包2.优惠卷3.积分4.实物5无',
    `num`        int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
    `detail`     longtext       NOT NULL COMMENT '优惠券信息',
    `price`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '价格',
    `order_id`   int(11) NOT NULL DEFAULT '0',
    `goods_id`   int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
    `raffled_at` timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at` timestamp      NOT NULL,
    `token`      varchar(255)   NOT NULL DEFAULT '' COMMENT '订单表token',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scratch_log_coupon_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_scratch_log_coupon_relation`;
CREATE TABLE `op_scratch_log_coupon_relation`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL COMMENT '用户优惠券id',
    `scratch_log_id` int(11) NOT NULL COMMENT '记录id',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`     timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scratch_order
-- ----------------------------
DROP TABLE IF EXISTS `op_scratch_order`;
CREATE TABLE `op_scratch_order`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `scratch_log_id` int(11) NOT NULL,
    `order_id`       int(11) NOT NULL,
    `created_at`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at`     timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `order_id` (`order_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scratch_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_scratch_setting`;
CREATE TABLE `op_scratch_setting`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`              int(11) NOT NULL,
    `title`                varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题',
    `type`                 smallint(1) NOT NULL COMMENT '1.天 2 用户',
    `probability`          int(11) NOT NULL DEFAULT '0' COMMENT '概率',
    `oppty`                int(11) NOT NULL DEFAULT '0' COMMENT '抽奖次数',
    `start_at`             timestamp    NOT NULL COMMENT '开始时间',
    `end_at`               timestamp    NOT NULL COMMENT '结束时间',
    `deplete_integral_num` int(11) NOT NULL DEFAULT '0' COMMENT '消耗积分',
    `rule`                 longtext     NOT NULL COMMENT '规则',
    `created_at`           timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`           timestamp    NOT NULL,
    `payment_type`         longtext     NOT NULL COMMENT '支付方式',
    `send_type`            longtext     NOT NULL COMMENT '发货方式',
    `is_sms`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
    `is_mail`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启邮件提醒',
    `is_print`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启打印',
    `bg_pic`               varchar(255) NOT NULL DEFAULT '' COMMENT '背景图',
    PRIMARY KEY (`id`),
    KEY                    `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_share
-- ----------------------------
DROP TABLE IF EXISTS `op_share`;
CREATE TABLE `op_share`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) NOT NULL,
    `user_id`           int(11) NOT NULL,
    `name`              varchar(255)   NOT NULL DEFAULT '' COMMENT '分销商名称',
    `mobile`            varchar(255)   NOT NULL DEFAULT '' COMMENT '分销商手机号',
    `money`             decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '可提现佣金',
    `status`            int(11) NOT NULL DEFAULT '0' COMMENT '用户申请分销商状态0--申请中 1--成功 2--失败',
    `total_money`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '累计佣金',
    `content`           longtext COMMENT '备注',
    `is_delete`         int(11) NOT NULL,
    `created_at`        datetime       NOT NULL,
    `updated_at`        datetime       NOT NULL,
    `deleted_at`        datetime       NOT NULL,
    `apply_at`          datetime                DEFAULT NULL COMMENT '申请时间',
    `become_at`         datetime                DEFAULT NULL COMMENT '成为分销商时间',
    `reason`            longtext COMMENT '审核原因',
    `first_children`    int(11) NOT NULL DEFAULT '0' COMMENT '直接下级数量',
    `all_children`      int(11) NOT NULL DEFAULT '0' COMMENT '所有下级数量',
    `all_money`         decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '总佣金数量(包括已发放和未发放且未退款的佣金）',
    `all_order`         int(11) NOT NULL DEFAULT '0' COMMENT '分销订单数量',
    `level`             int(11) NOT NULL DEFAULT '0' COMMENT '分销商等级',
    `level_at`          timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '成为分销商等级时间',
    `delete_first_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除后是否第一次展示',
    `form`              text COMMENT '分销商自定义表单',
    PRIMARY KEY (`id`),
    KEY                 `mall_id` (`mall_id`),
    KEY                 `is_delete` (`is_delete`),
    KEY                 `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销商信息';

-- ----------------------------
-- Table structure for op_share_cash
-- ----------------------------
DROP TABLE IF EXISTS `op_share_cash`;
CREATE TABLE `op_share_cash`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `order_no`       varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
    `service_charge` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',
    `type`           varchar(255)   NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
    `extra`          longtext COMMENT '额外信息 例如微信账号、支付宝账号等',
    `status`         int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',
    `is_delete`      int(11) NOT NULL DEFAULT '0',
    `created_at`     datetime       NOT NULL,
    `updated_at`     datetime       NOT NULL,
    `deleted_at`     datetime       NOT NULL,
    `content`        longtext,
    PRIMARY KEY (`id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='提现记录表';

-- ----------------------------
-- Table structure for op_share_cash_log
-- ----------------------------
DROP TABLE IF EXISTS `op_share_cash_log`;
CREATE TABLE `op_share_cash_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        int(11) NOT NULL DEFAULT '1' COMMENT '类型 1--收入 2--支出',
    `price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '变动佣金',
    `desc`        longtext,
    `custom_desc` longtext,
    `is_delete`   int(11) NOT NULL DEFAULT '0',
    `created_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`  datetime       NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `user_id` (`user_id`),
    KEY           `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_share_level
-- ----------------------------
DROP TABLE IF EXISTS `op_share_level`;
CREATE TABLE `op_share_level`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `level`          int(11) NOT NULL DEFAULT '1' COMMENT '分销等级1~100',
    `name`           varchar(255)   NOT NULL DEFAULT '' COMMENT '分销等级名称',
    `condition_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '升级条件：1--下线用户数|2--累计佣金|3--已提现佣金',
    `condition`      decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '下线用户数（人）|累计佣金数（元）|已提现佣金数（元）',
    `price_type`     tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销佣金类型：1--百分比|2--固定金额',
    `first`          decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '一级分销佣金数（元）',
    `status`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `created_at`     timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp      NOT NULL,
    `deleted_at`     timestamp      NOT NULL,
    `second`         decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '二级分销佣金数（元）',
    `third`          decimal(11, 2) NOT NULL DEFAULT '0.00' COMMENT '三级分销佣金数（元）',
    `is_auto_level`  tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用自动升级',
    `rule`           varchar(255)   NOT NULL DEFAULT '' COMMENT '等级说明',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_share_order
-- ----------------------------
DROP TABLE IF EXISTS `op_share_order`;
CREATE TABLE `op_share_order`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `order_id`           int(11) NOT NULL,
    `order_detail_id`    int(11) NOT NULL,
    `user_id`            int(11) NOT NULL COMMENT '购物者用户id',
    `first_parent_id`    int(11) NOT NULL DEFAULT '0' COMMENT '上一级用户id',
    `second_parent_id`   int(11) NOT NULL DEFAULT '0' COMMENT '上二级用户id',
    `third_parent_id`    int(11) NOT NULL DEFAULT '0' COMMENT '上三级用户id',
    `first_price`        decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '上一级分销佣金',
    `second_price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '上二级分销佣金',
    `third_price`        decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '上三级分销佣金',
    `is_refund`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未退款 1退款',
    `is_transfer`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '佣金发放状态：0=未发放，1=已发放',
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `created_at`         timestamp NULL DEFAULT NULL,
    `updated_at`         timestamp NULL DEFAULT NULL,
    `deleted_at`         timestamp NULL DEFAULT NULL,
    `price`              decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '用于分销的金额',
    `first_share_type`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '一级分销的分销类型',
    `first_share_price`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
    `second_share_type`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '二级分销的分销类型',
    `second_share_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
    `third_share_type`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '三级分销的分销类型',
    `third_share_price`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '三级佣金',
    `flag`               tinyint(1) NOT NULL DEFAULT '0' COMMENT '修改记录 0--售后优化之前的分销订单 1--售后优化之后的订单',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `order_id` (`order_id`),
    KEY                  `order_detail_id` (`order_detail_id`),
    KEY                  `user_id` (`user_id`),
    KEY                  `first_parent_id` (`first_parent_id`),
    KEY                  `second_parent_id` (`second_parent_id`),
    KEY                  `third_parent_id` (`third_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销订单';

-- ----------------------------
-- Table structure for op_share_order_log
-- ----------------------------
DROP TABLE IF EXISTS `op_share_order_log`;
CREATE TABLE `op_share_order_log`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `share_setting`    longtext  NOT NULL COMMENT '分销设置情况',
    `order_share_info` longtext  NOT NULL COMMENT '订单分销情况',
    `created_at`       timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_share_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_share_setting`;
CREATE TABLE `op_share_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(150) NOT NULL,
    `value`      longtext     NOT NULL,
    `created_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `is_delete`  int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',
    `deleted_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分销设置';

-- ----------------------------
-- Table structure for op_statistics_data_log
-- ----------------------------
DROP TABLE IF EXISTS `op_statistics_data_log`;
CREATE TABLE `op_statistics_data_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `key`        varchar(100) NOT NULL DEFAULT '',
    `value`      int(11) NOT NULL DEFAULT '0',
    `time_stamp` int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `key` (`key`),
    KEY          `value` (`value`),
    KEY          `time_stamp` (`time_stamp`),
    KEY          `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_statistics_user_log
-- ----------------------------
DROP TABLE IF EXISTS `op_statistics_user_log`;
CREATE TABLE `op_statistics_user_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `user_id`    int(11) NOT NULL DEFAULT '0',
    `num`        int(11) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `time_stamp` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`),
    KEY          `num` (`num`),
    KEY          `created_at` (`created_at`),
    KEY          `is_delete` (`is_delete`),
    KEY          `time_stamp` (`time_stamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_activity
-- ----------------------------
DROP TABLE IF EXISTS `op_step_activity`;
CREATE TABLE `op_step_activity`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `title`         varchar(255)   NOT NULL,
    `currency`      decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '奖金池',
    `step_num`      int(11) NOT NULL DEFAULT '0' COMMENT '挑战步数',
    `bail_currency` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '保证金',
    `status`        tinyint(1) NOT NULL DEFAULT '0',
    `type`          smallint(1) NOT NULL DEFAULT '0' COMMENT '0进行中 1 已完成 2 已解散',
    `begin_at`      date           NOT NULL COMMENT '开始时间',
    `end_at`        date           NOT NULL COMMENT '结束时间',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`    timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`    timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_activity_info
-- ----------------------------
DROP TABLE IF EXISTS `op_step_activity_info`;
CREATE TABLE `op_step_activity_info`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `activity_log_id` int(11) NOT NULL COMMENT 'a',
    `num`             int(11) NOT NULL COMMENT '提交步数',
    `open_date`       date      NOT NULL COMMENT '创建时间',
    `created_at`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_activity_log
-- ----------------------------
DROP TABLE IF EXISTS `op_step_activity_log`;
CREATE TABLE `op_step_activity_log`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `step_id`         int(11) NOT NULL,
    `activity_id`     int(11) NOT NULL,
    `step_currency`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '缴纳金',
    `reward_currency` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '奖励金额',
    `status`          tinyint(255) NOT NULL DEFAULT '0' COMMENT '0报名1达标  2成功 3失败 4解散',
    `created_at`      timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `raffled_at`      timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_ad
-- ----------------------------
DROP TABLE IF EXISTS `op_step_ad`;
CREATE TABLE `op_step_ad`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `unit_id`     varchar(255) NOT NULL DEFAULT '' COMMENT '广告id',
    `site`        int(11) NOT NULL DEFAULT '0' COMMENT '位置',
    `status`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`  timestamp    NOT NULL,
    `type`        varchar(255) NOT NULL DEFAULT '' COMMENT '流量主类型',
    `pic_url`     varchar(255) NOT NULL DEFAULT '' COMMENT '广告封面',
    `video_url`   varchar(255) NOT NULL DEFAULT '' COMMENT '广告视频',
    `reward_data` longtext     NOT NULL COMMENT '奖励数据',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_ad_coupon
-- ----------------------------
DROP TABLE IF EXISTS `op_step_ad_coupon`;
CREATE TABLE `op_step_ad_coupon`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `user_coupon_id` int(11) NOT NULL,
    `is_delete`      tinyint(2) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`     timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at`     timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_ad_log
-- ----------------------------
DROP TABLE IF EXISTS `op_step_ad_log`;
CREATE TABLE `op_step_ad_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `ad_id`      int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `raffled_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_banner_relation
-- ----------------------------
DROP TABLE IF EXISTS `op_step_banner_relation`;
CREATE TABLE `op_step_banner_relation`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `banner_id`  int(11) NOT NULL COMMENT '轮播图id',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_daily
-- ----------------------------
DROP TABLE IF EXISTS `op_step_daily`;
CREATE TABLE `op_step_daily`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `step_id`    int(11) NOT NULL,
    `ratio`      int(11) NOT NULL COMMENT '兑换概率',
    `real_num`   int(11) NOT NULL COMMENT '真实步数',
    `num`        int(11) NOT NULL COMMENT '兑换加成后数量',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `step_id` (`step_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_step_goods`;
CREATE TABLE `op_step_goods`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `currency`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '活力币',
    `goods_id`   int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    `deleted_at` timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `op_step_goods_attr`;
CREATE TABLE `op_step_goods_attr`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`  int(11) NOT NULL,
    `attr_id`  int(11) NOT NULL COMMENT '规格',
    `currency` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '活力币',
    `goods_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY        `attr_id` (`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_log
-- ----------------------------
DROP TABLE IF EXISTS `op_step_log`;
CREATE TABLE `op_step_log`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `step_id`    int(11) NOT NULL,
    `type`       int(11) NOT NULL COMMENT '1收入 2 支出',
    `currency`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '活力币',
    `remark`     varchar(255)   NOT NULL DEFAULT '' COMMENT '备注',
    `data`       longtext       NOT NULL COMMENT '详情',
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_order
-- ----------------------------
DROP TABLE IF EXISTS `op_step_order`;
CREATE TABLE `op_step_order`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `order_id`        int(11) NOT NULL DEFAULT '0',
    `mall_id`         int(11) NOT NULL,
    `num`             int(11) NOT NULL COMMENT '商品数量',
    `total_pay_price` decimal(10, 2) NOT NULL COMMENT '订单实际支付价格',
    `user_id`         int(11) NOT NULL COMMENT '用户ID',
    `currency`        decimal(10, 2) NOT NULL,
    `created_at`      timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`      timestamp      NOT NULL,
    `token`           varchar(255)   NOT NULL,
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY               `order_id` (`order_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_step_setting`;
CREATE TABLE `op_step_setting`
(
    `id`                        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                   int(11) NOT NULL,
    `convert_max`               int(11) NOT NULL DEFAULT '0' COMMENT '每日最高兑换数',
    `convert_ratio`             int(11) NOT NULL DEFAULT '0' COMMENT '兑换比率',
    `currency_name`             varchar(255) NOT NULL DEFAULT '' COMMENT '活力币别名',
    `activity_pic`              varchar(255) NOT NULL DEFAULT '' COMMENT '活动背景',
    `ranking_pic`               varchar(255) NOT NULL DEFAULT '' COMMENT '排行榜背景',
    `qrcode_pic`                longtext     NOT NULL COMMENT '海报缩略图',
    `invite_ratio`              int(11) NOT NULL DEFAULT '0' COMMENT '邀请比率',
    `remind_at`                 varchar(255) NOT NULL DEFAULT '16' COMMENT '提醒时间',
    `rule`                      longtext     NOT NULL COMMENT '活动规则',
    `activity_rule`             longtext     NOT NULL COMMENT '活动规则',
    `ranking_num`               int(11) NOT NULL DEFAULT '0' COMMENT '全国排行限制',
    `title`                     varchar(255) NOT NULL DEFAULT '' COMMENT '小程序标题',
    `share_title`               varchar(255) NOT NULL DEFAULT '' COMMENT '转发标题',
    `qrcode_title`              varchar(255) NOT NULL DEFAULT '' COMMENT '海报文字',
    `created_at`                timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                timestamp    NOT NULL,
    `payment_type`              longtext     NOT NULL COMMENT '支付方式',
    `send_type`                 longtext     NOT NULL COMMENT '发货方式',
    `is_share`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                    tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
    `is_mail`                   tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启邮件提醒',
    `is_print`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启打印',
    `is_territorial_limitation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启区域允许购买',
    `goods_poster`              longtext     NOT NULL COMMENT '自定义海报',
    `step_poster`               longtext     NOT NULL COMMENT '步数海报',
    `share_pic`                 varchar(255) NOT NULL DEFAULT '' COMMENT '分享图片',
    PRIMARY KEY (`id`),
    KEY                         `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_step_user
-- ----------------------------
DROP TABLE IF EXISTS `op_step_user`;
CREATE TABLE `op_step_user`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`       int(11) NOT NULL,
    `user_id`       int(11) NOT NULL COMMENT '用户ID',
    `ratio`         int(11) NOT NULL DEFAULT '0' COMMENT '概率加成',
    `step_currency` decimal(10, 2) NOT NULL,
    `parent_id`     int(11) NOT NULL DEFAULT '0' COMMENT '邀请ID',
    `invite_ratio`  int(11) NOT NULL DEFAULT '0' COMMENT '邀请好友加成',
    `is_remind`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒',
    `is_delete`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`    timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`    timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY             `mall_id` (`mall_id`),
    KEY             `user_id` (`user_id`),
    KEY             `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_stock_bonus_log
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_bonus_log`;
CREATE TABLE `op_stock_bonus_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL DEFAULT '0',
    `bonus_type`  tinyint(4) NOT NULL DEFAULT '0' COMMENT '1按周，2按月',
    `bonus_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '分红金额',
    `bonus_rate`  decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '当时的分红比例',
    `order_num`   int(11) NOT NULL DEFAULT '0' COMMENT '分红订单数',
    `stock_num`   int(11) NOT NULL DEFAULT '0' COMMENT '当时股东人数',
    `start_time`  timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-开始时间',
    `end_time`    timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间段-结束时间',
    `created_at`  timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp      NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_stock_cash
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_cash`;
CREATE TABLE `op_stock_cash`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `order_no`       varchar(255)   NOT NULL DEFAULT '' COMMENT '订单号',
    `price`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
    `service_charge` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费（%）',
    `type`           varchar(255)   NOT NULL DEFAULT '' COMMENT '提现方式 auto--自动打款 wechat--微信打款 alipay--支付宝打款 bank--银行转账 balance--打款到余额',
    `extra`          longtext COMMENT '额外信息 例如微信账号、支付宝账号等',
    `status`         int(11) NOT NULL DEFAULT '0' COMMENT '提现状态 0--申请 1--同意 2--已打款 3--驳回',
    `is_delete`      int(11) NOT NULL DEFAULT '0',
    `created_at`     datetime       NOT NULL,
    `updated_at`     datetime       NOT NULL,
    `deleted_at`     datetime       NOT NULL,
    `content`        longtext,
    PRIMARY KEY (`id`) USING BTREE,
    KEY              `mall_id` (`mall_id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='提现记录表';

-- ----------------------------
-- Table structure for op_stock_cash_log
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_cash_log`;
CREATE TABLE `op_stock_cash_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `type`        int(11) NOT NULL DEFAULT '1' COMMENT '类型 1--收入 2--支出',
    `price`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '变动佣金',
    `desc`        longtext,
    `custom_desc` longtext,
    `level_id`    int(11) DEFAULT '0' COMMENT '当时的股东等级',
    `level_name`  varchar(100)            DEFAULT NULL,
    `order_num`   int(11) DEFAULT '0',
    `bonus_rate`  decimal(10, 2)          DEFAULT '0.00' COMMENT '当时的分红比例',
    `bonus_id`    int(11) DEFAULT '0' COMMENT '股东完成分红记录ID',
    `is_delete`   int(11) NOT NULL DEFAULT '0',
    `created_at`  datetime       NOT NULL,
    `updated_at`  datetime       NOT NULL,
    `deleted_at`  datetime       NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `mall_id` (`mall_id`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分红日志';

-- ----------------------------
-- Table structure for op_stock_level
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_level`;
CREATE TABLE `op_stock_level`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `name`       varchar(100)   NOT NULL DEFAULT '' COMMENT '等级名称',
    `bonus_rate` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '分红比例',
    `condition`  int(11) NOT NULL DEFAULT '0' COMMENT '升级条件，0不自动升级',
    `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认等级，0否1是',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at` timestamp      NOT NULL,
    `created_at` timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp      NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='股东等级表';

-- ----------------------------
-- Table structure for op_stock_level_up
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_level_up`;
CREATE TABLE `op_stock_level_up`
(
    `id`      int(11) NOT NULL AUTO_INCREMENT,
    `mall_id` int(11) NOT NULL DEFAULT '0',
    `type`    tinyint(2) NOT NULL DEFAULT '1' COMMENT '1下线总人数，2累计佣金总额，3已提现佣金总额，4分销订单总数，5分销订单总金额',
    `remark`  text,
    PRIMARY KEY (`id`) USING BTREE,
    KEY       `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='股东等级升级条件';

-- ----------------------------
-- Table structure for op_stock_order
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_order`;
CREATE TABLE `op_stock_order`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL DEFAULT '0',
    `order_id`        int(11) NOT NULL DEFAULT '0',
    `total_pay_price` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '订单实付金额',
    `is_bonus`        tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已分红，0未分红',
    `bonus_time`      timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '分红时间',
    `bonus_id`        int(11) NOT NULL DEFAULT '0' COMMENT '股东完成分红记录ID',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at`      timestamp      NOT NULL,
    `created_at`      timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp      NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY               `mall_id` (`mall_id`),
    KEY               `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='分红池';

-- ----------------------------
-- Table structure for op_stock_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_setting`;
CREATE TABLE `op_stock_setting`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      text         NOT NULL,
    `created_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `is_delete`  int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--未删除 1--已删除',
    `deleted_at` timestamp    NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='股东分红设置';

-- ----------------------------
-- Table structure for op_stock_user
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_user`;
CREATE TABLE `op_stock_user`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `user_id`    int(11) NOT NULL DEFAULT '0',
    `level_id`   int(11) NOT NULL DEFAULT '0' COMMENT '对应等级表ID',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '-2被拒或移除后再次申请没提交 -1移除 0审核中，1同意，2拒绝',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at` timestamp NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `applyed_at` timestamp NOT NULL COMMENT '申请时间',
    `agreed_at`  timestamp NOT NULL COMMENT '审核时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='股东表';

-- ----------------------------
-- Table structure for op_stock_user_info
-- ----------------------------
DROP TABLE IF EXISTS `op_stock_user_info`;
CREATE TABLE `op_stock_user_info`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `user_id`     int(11) NOT NULL DEFAULT '0',
    `name`        varchar(100)            DEFAULT '' COMMENT '股东姓名',
    `phone`       varchar(11)             DEFAULT '' COMMENT '股东手机号',
    `all_bonus`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '累计分红',
    `total_bonus` decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '当前分红',
    `out_bonus`   decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '已提现分红',
    `remark`      varchar(200)   NOT NULL DEFAULT '' COMMENT '备注',
    `reason`      text           NOT NULL COMMENT '拒绝理由',
    `created_at`  timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp      NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY           `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='股东信息表';

-- ----------------------------
-- Table structure for op_store
-- ----------------------------
DROP TABLE IF EXISTS `op_store`;
CREATE TABLE `op_store`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `mch_id`           int(11) NOT NULL DEFAULT '0',
    `name`             varchar(65)  NOT NULL DEFAULT '' COMMENT '店铺名称',
    `mobile`           varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
    `address`          varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
    `province_id`      int(11) NOT NULL DEFAULT '0',
    `city_id`          int(11) NOT NULL DEFAULT '0',
    `district_id`      int(11) NOT NULL DEFAULT '0',
    `longitude`        varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
    `latitude`         varchar(255) NOT NULL DEFAULT '' COMMENT '纬度',
    `score`            int(11) NOT NULL DEFAULT '5' COMMENT '店铺评分',
    `cover_url`        varchar(255) NOT NULL DEFAULT '' COMMENT '店铺封面图',
    `pic_url`          text         NOT NULL COMMENT '门店轮播图',
    `business_hours`   varchar(125) NOT NULL DEFAULT '' COMMENT '营业时间',
    `description`      longtext     NOT NULL COMMENT '门店描述',
    `scope`            mediumtext   NOT NULL COMMENT '门店经营范围',
    `is_default`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认总店0.否|1.是',
    `created_at`       timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp    NOT NULL,
    `deleted_at`       timestamp    NOT NULL,
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    `is_all_day`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全天营业0.否|1.是',
    `extra_attributes` text         NOT NULL,
    `status`           tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态开关',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `mch_id` (`mch_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_cashier
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_cashier`;
CREATE TABLE `op_teller_cashier`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `user_id`    int(11) NOT NULL COMMENT '用户ID',
    `number`     varchar(255) NOT NULL COMMENT '收银员编号',
    `store_id`   int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
    `creator_id` int(11) NOT NULL COMMENT '创建者ID',
    `status`     tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',
    `push_money` decimal(10, 2) DEFAULT '0.00' COMMENT '提成总金额',
    `sale_money` decimal(10, 2) DEFAULT '0.00' COMMENT '销售总金额',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `index` (`mall_id`,`mch_id`,`user_id`,`store_id`,`creator_id`,`status`,`is_delete`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_orders`;
CREATE TABLE `op_teller_orders`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) DEFAULT NULL,
    `mch_id`            int(11) DEFAULT '0',
    `order_id`          int(11) DEFAULT '0' COMMENT '订单ID',
    `re_order_id`       int(11) DEFAULT '0' COMMENT '充值订单ID',
    `cashier_id`        int(11) DEFAULT NULL COMMENT '收银员ID',
    `sales_id`          int(11) DEFAULT '0' COMMENT '导购员ID',
    `order_type`        varchar(255)   DEFAULT NULL COMMENT '订单类型',
    `add_money`         decimal(10, 2) DEFAULT '0.00' COMMENT '订单加价',
    `change_price_type` varchar(255)   DEFAULT NULL COMMENT '改价类型 加价|减价',
    `change_price`      decimal(10, 2) DEFAULT '0.00' COMMENT '订单改价金额',
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL,
    `is_refund`         tinyint(1) DEFAULT '0' COMMENT '是否有退款0.否|1.是',
    `refund_money`      decimal(10, 2) DEFAULT '0.00' COMMENT '退款总金额',
    `order_query`       int(11) DEFAULT '0' COMMENT '付款码订单查询次数',
    `is_pay`            int(11) DEFAULT '0' COMMENT '是否支付0.未付款|1.已付款',
    `pay_type`          int(11) DEFAULT '0' COMMENT '支付方式',
    `work_log_id`       int(11) DEFAULT '0' COMMENT '交班记录ID',
    `is_statistics`     tinyint(1) DEFAULT '0' COMMENT '是否统计0.否|1.是',
    PRIMARY KEY (`id`),
    KEY                 `index` (`mall_id`,`mch_id`,`order_id`,`re_order_id`,`cashier_id`,`sales_id`,`order_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_teller_printer_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_printer_setting`;
CREATE TABLE `op_teller_printer_setting`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `mch_id`          int(11) NOT NULL DEFAULT '0',
    `store_id`        int(11) DEFAULT '0',
    `printer_id`      int(11) NOT NULL COMMENT '打印机id',
    `status`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1启用',
    `type`            text COMMENT '打印类型',
    `order_send_type` text COMMENT '发货方式',
    `show_type`       longtext  NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单',
    `big`             int(11) NOT NULL DEFAULT '0' COMMENT '倍数',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp NOT NULL,
    `deleted_at`      timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY               `mch_id` (`mch_id`),
    KEY               `is_delete` (`is_delete`),
    KEY               `mall_id` (`mall_id`),
    KEY               `status` (`status`),
    KEY               `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_push_order
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_push_order`;
CREATE TABLE `op_teller_push_order`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) DEFAULT NULL,
    `mch_id`           int(11) DEFAULT '0',
    `user_type`        varchar(65)    DEFAULT NULL COMMENT '用户类型',
    `order_type`       varchar(65)    DEFAULT NULL COMMENT '订单类型',
    `teller_order_id`  int(11) NOT NULL COMMENT '收银台订单ID',
    `order_id`         int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
    `re_order_id`      int(11) DEFAULT '0' COMMENT '充值订单ID',
    `push_type`        varchar(65)    DEFAULT NULL COMMENT '提成类型',
    `push_order_money` decimal(10, 2) DEFAULT '0.00' COMMENT '按订单提成金额 ',
    `push_percent`     decimal(10, 2) DEFAULT '0.00' COMMENT '按百分比提成',
    `sales_id`         int(11) DEFAULT '0' COMMENT '导购员ID',
    `cashier_id`       int(11) DEFAULT '0' COMMENT '收银员ID',
    `push_money`       decimal(10, 2) DEFAULT '0.00' COMMENT '订单过售后最终提成金额',
    `status`           varchar(255)   DEFAULT NULL COMMENT '订单状态',
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    `is_delete`        tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `index` (`mall_id`,`mch_id`,`user_type`,`order_type`,`order_id`,`re_order_id`,`push_type`,`sales_id`,`cashier_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_teller_sales
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_sales`;
CREATE TABLE `op_teller_sales`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `number`     varchar(255) NOT NULL COMMENT '导购员编号',
    `name`       varchar(255) NOT NULL COMMENT '姓名',
    `head_url`   varchar(255) NOT NULL COMMENT '头像',
    `mobile`     varchar(255) NOT NULL COMMENT '电话',
    `store_id`   int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
    `creator_id` int(11) NOT NULL COMMENT '创建者ID',
    `status`     tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',
    `push_money` decimal(10, 2) DEFAULT '0.00' COMMENT '提成总金额',
    `sale_money` decimal(10, 2) DEFAULT '0.00' COMMENT '销售总金额',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `index` (`mall_id`,`mch_id`,`store_id`,`creator_id`,`status`,`is_delete`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_work_log
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_work_log`;
CREATE TABLE `op_teller_work_log`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) DEFAULT NULL,
    `mch_id`           int(11) DEFAULT '0',
    `store_id`         int(11) DEFAULT '0' COMMENT '门店ID',
    `start_time`       timestamp NULL DEFAULT NULL COMMENT '上班时间',
    `end_time`         timestamp NULL DEFAULT NULL COMMENT '交班时间',
    `cashier_id`       int(11) DEFAULT NULL COMMENT '收银员ID',
    `status`           varchar(255) DEFAULT NULL COMMENT '交班状态pending 上班中|finish 交班完成',
    `extra_attributes` text COMMENT '交班详细信息',
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    `is_delete`        int(11) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `index` (`mall_id`,`mch_id`,`store_id`,`cashier_id`,`status`,`is_delete`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_template_record
-- ----------------------------
DROP TABLE IF EXISTS `op_template_record`;
CREATE TABLE `op_template_record`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `status`     int(1) NOT NULL DEFAULT '0' COMMENT '模板消息是否发送成功0--失败|1--成功',
    `data`       longtext NOT NULL COMMENT '模板消息内容',
    `error`      longtext NOT NULL COMMENT '错误信息',
    `created_at` timestamp NULL DEFAULT NULL,
    `token`      varchar(255) DEFAULT NULL COMMENT '模板消息发送标示',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `user_id` (`user_id`),
    KEY          `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='模板消息发送记录表';

-- ----------------------------
-- Table structure for op_topic
-- ----------------------------
DROP TABLE IF EXISTS `op_topic`;
CREATE TABLE `op_topic`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                int(11) NOT NULL,
    `type`                   int(11) NOT NULL COMMENT '分类',
    `title`                  varchar(255) NOT NULL COMMENT '名称',
    `sub_title`              varchar(255) NOT NULL DEFAULT '' COMMENT '副标题（未用）',
    `content`                longtext     NOT NULL COMMENT '专题内容',
    `layout`                 smallint(1) NOT NULL DEFAULT '0' COMMENT '布局方式：0=小图，1=大图模式',
    `sort`                   int(11) NOT NULL DEFAULT '1' COMMENT '排序：升序',
    `cover_pic`              varchar(255) NOT NULL COMMENT '封面图',
    `read_count`             int(11) NOT NULL DEFAULT '0' COMMENT '阅读量',
    `agree_count`            int(11) NOT NULL DEFAULT '0' COMMENT '点赞数（未用）',
    `virtual_read_count`     int(11) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量',
    `virtual_agree_count`    int(11) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数（未用）',
    `virtual_favorite_count` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟收藏量',
    `qrcode_pic`             varchar(255) NOT NULL DEFAULT '' COMMENT '自定义分享图片(海报图)',
    `app_share_title`        varchar(65)  NOT NULL DEFAULT '' COMMENT '自定义分享标题',
    `is_chosen`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精选',
    `is_delete`              tinyint(11) NOT NULL DEFAULT '0' COMMENT '删除',
    `deleted_at`             timestamp    NOT NULL,
    `created_at`             timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`             timestamp    NOT NULL,
    `pic_list`               longtext,
    `detail`                 longtext,
    `abstract`               varchar(255) NOT NULL DEFAULT '' COMMENT '摘要',
    PRIMARY KEY (`id`),
    KEY                      `store_id` (`mall_id`) USING BTREE,
    KEY                      `is_delete` (`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_topic_favorite
-- ----------------------------
DROP TABLE IF EXISTS `op_topic_favorite`;
CREATE TABLE `op_topic_favorite`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL,
    `topic_id`   int(11) NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `deleted_at` timestamp NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_topic_type
-- ----------------------------
DROP TABLE IF EXISTS `op_topic_type`;
CREATE TABLE `op_topic_type`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(255) NOT NULL COMMENT '名称',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '0隐藏 1开启',
    `sort`       int(11) NOT NULL DEFAULT '1' COMMENT '排序',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp    NOT NULL,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_ttapp_config
-- ----------------------------
DROP TABLE IF EXISTS `op_ttapp_config`;
CREATE TABLE `op_ttapp_config`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `mch_id`             varchar(64)  DEFAULT NULL COMMENT '商户号',
    `app_key`            varchar(64)  DEFAULT NULL,
    `app_secret`         varchar(64)  DEFAULT NULL,
    `pay_app_secret`     varchar(128) DEFAULT NULL,
    `pay_app_id`         varchar(64)  DEFAULT NULL,
    `alipay_app_id`      varchar(128) DEFAULT NULL,
    `alipay_public_key`  text,
    `alipay_private_key` text,
    `created_at`         timestamp NULL DEFAULT NULL,
    `updated_at`         timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY                  `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for op_ttapp_template
-- ----------------------------
DROP TABLE IF EXISTS `op_ttapp_template`;
CREATE TABLE `op_ttapp_template`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(65)  NOT NULL DEFAULT '',
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_user
-- ----------------------------
DROP TABLE IF EXISTS `op_user`;
CREATE TABLE `op_user`
(
    `id`           int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `mch_id`       int(11) NOT NULL DEFAULT '0' COMMENT '多商户ID',
    `username`     varchar(64)  NOT NULL,
    `password`     varchar(128) NOT NULL,
    `nickname`     varchar(100) NOT NULL DEFAULT '',
    `auth_key`     varchar(128) NOT NULL,
    `access_token` varchar(128) NOT NULL,
    `mobile`       varchar(255) NOT NULL DEFAULT '',
    `unionid`      varchar(64)  NOT NULL DEFAULT '',
    `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp    NOT NULL,
    `deleted_at`   timestamp    NOT NULL,
    `is_delete`    tinyint(1) NOT NULL DEFAULT '0',
    `email`        varchar(50)  DEFAULT NULL COMMENT '邮箱',
    PRIMARY KEY (`id`),
    KEY            `username` (`username`),
    KEY            `access_token` (`access_token`),
    KEY            `mall_id` (`mall_id`),
    KEY            `mch_id` (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_auth_login
-- ----------------------------
DROP TABLE IF EXISTS `op_user_auth_login`;
CREATE TABLE `op_user_auth_login`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `user_id`    int(11) NOT NULL DEFAULT '0',
    `token`      varchar(255) NOT NULL DEFAULT '',
    `is_pass`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否确认登录',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_card
-- ----------------------------
DROP TABLE IF EXISTS `op_user_card`;
CREATE TABLE `op_user_card`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `user_id`         int(11) NOT NULL,
    `card_id`         int(11) NOT NULL,
    `name`            varchar(255) NOT NULL COMMENT '名称',
    `pic_url`         varchar(255) NOT NULL COMMENT '图片',
    `content`         longtext     NOT NULL COMMENT '详情',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`      timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp    NOT NULL,
    `deleted_at`      timestamp    NOT NULL,
    `is_use`          int(11) NOT NULL DEFAULT '0' COMMENT '是否使用 0--未使用 1--已使用',
    `clerk_id`        int(11) NOT NULL DEFAULT '0' COMMENT '核销人id',
    `store_id`        int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
    `clerked_at`      timestamp    NOT NULL COMMENT ' 核销时间',
    `order_id`        int(11) NOT NULL DEFAULT '0' COMMENT '发放卡券的订单id',
    `order_detail_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单详情ID',
    `data`            longtext COMMENT '额外信息字段',
    `start_time`      timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `end_time`        timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `use_number`      int(11) NOT NULL DEFAULT '0' COMMENT '卡券已核销次数',
    `number`          int(11) NOT NULL DEFAULT '1' COMMENT '卡券可核销次数',
    `receive_id`      int(11) NOT NULL DEFAULT '0' COMMENT '转赠领取的用户id',
    `parent_card_id`  int(11) NOT NULL DEFAULT '0' COMMENT '转赠的用户卡券id',
    `remark`          varchar(255)          DEFAULT '' COMMENT '简单备注来源',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`),
    KEY               `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_center
-- ----------------------------
DROP TABLE IF EXISTS `op_user_center`;
CREATE TABLE `op_user_center`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `config`     longblob     NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deleted_at` timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `name`       varchar(255) NOT NULL DEFAULT '',
    `is_recycle` tinyint(1) NOT NULL DEFAULT '0',
    `platform`   varchar(255) NOT NULL DEFAULT '',
    `version`    tinyint(1) DEFAULT '1' COMMENT '版本',
    `data`       longblob     NOT NULL COMMENT '版本2的数据',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_user_coupon
-- ----------------------------
DROP TABLE IF EXISTS `op_user_coupon`;
CREATE TABLE `op_user_coupon`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `mch_id`           int(11) NOT NULL DEFAULT '0' COMMENT '多商户id',
    `user_id`          int(11) NOT NULL COMMENT '用户',
    `coupon_id`        int(11) NOT NULL COMMENT '优惠卷',
    `sub_price`        decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '满减',
    `discount`         decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
    `coupon_min_price` decimal(10, 2) NOT NULL COMMENT '最低消费金额',
    `type`             int(11) NOT NULL DEFAULT '1' COMMENT '优惠券类型：1=折扣，2=满减',
    `start_time`       timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期开始时间',
    `end_time`         timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效期结束时间',
    `is_use`           tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已使用：0=未使用，1=已使用',
    `is_delete`        smallint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`       timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp      NOT NULL,
    `deleted_at`       timestamp      NOT NULL,
    `receive_type`     varchar(255)   NOT NULL DEFAULT '' COMMENT '获取方式',
    `coupon_data`      longtext       NOT NULL COMMENT '优惠券信息json格式',
    `discount_limit`   decimal(10, 2)          DEFAULT NULL COMMENT '折扣优惠上限',
    PRIMARY KEY (`id`) USING BTREE,
    KEY                `store_id` (`mall_id`) USING BTREE,
    KEY                `user_id` (`user_id`) USING BTREE,
    KEY                `mch_id` (`mch_id`) USING BTREE,
    KEY                `coupon_id` (`coupon_id`) USING BTREE,
    KEY                `is_delete` (`is_delete`) USING BTREE,
    KEY                `is_use` (`is_use`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_coupon_auto
-- ----------------------------
DROP TABLE IF EXISTS `op_user_coupon_auto`;
CREATE TABLE `op_user_coupon_auto`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `user_coupon_id` int(11) NOT NULL,
    `auto_coupon_id` int(11) NOT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    PRIMARY KEY (`id`),
    KEY              `user_coupon_id` (`user_coupon_id`),
    KEY              `auto_coupon_id` (`auto_coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_coupon_center
-- ----------------------------
DROP TABLE IF EXISTS `op_user_coupon_center`;
CREATE TABLE `op_user_coupon_center`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL DEFAULT '0' COMMENT '商城ID',
    `user_coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券ID',
    `user_id`        int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `is_delete`      int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--不删除 1--删除',
    `created_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `deleted_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `user_coupon_id` (`user_coupon_id`),
    KEY              `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户领取的优惠券关联表（领券中心）';

-- ----------------------------
-- Table structure for op_user_coupon_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_user_coupon_goods`;
CREATE TABLE `op_user_coupon_goods`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL DEFAULT '0' COMMENT '商城ID',
    `user_coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券ID',
    `user_id`        int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `goods_id`       int(11) NOT NULL COMMENT '商品ID',
    `is_delete`      int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0--不删除 1--删除',
    `created_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    `deleted_at`     timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '删除时间',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`) USING BTREE,
    KEY              `user_coupon_id` (`user_coupon_id`) USING BTREE,
    KEY              `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='随商品赠送优惠券关联表';

-- ----------------------------
-- Table structure for op_user_coupon_member
-- ----------------------------
DROP TABLE IF EXISTS `op_user_coupon_member`;
CREATE TABLE `op_user_coupon_member`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `user_id`        int(11) NOT NULL,
    `member_level`   int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
    `user_coupon_id` int(11) NOT NULL,
    `is_delete`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_identity
-- ----------------------------
DROP TABLE IF EXISTS `op_user_identity`;
CREATE TABLE `op_user_identity`
(
    `id`             int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户身份表',
    `user_id`        int(11) NOT NULL,
    `is_super_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为超级管理员',
    `is_admin`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为管理员',
    `is_operator`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为操作员|员工',
    `member_level`   int(11) NOT NULL DEFAULT '0' COMMENT '会员等级:0.普通成员',
    `is_distributor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为分销商',
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY              `user_id` (`user_id`),
    KEY              `is_super_admin` (`is_super_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_info
-- ----------------------------
DROP TABLE IF EXISTS `op_user_info`;
CREATE TABLE `op_user_info`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`          int(11) NOT NULL,
    `avatar`           varchar(255)   NOT NULL DEFAULT '' COMMENT '头像',
    `platform_user_id` varchar(255)   NOT NULL DEFAULT '' COMMENT '用户所属平台的用户id',
    `integral`         int(11) NOT NULL DEFAULT '0' COMMENT '积分',
    `total_integral`   int(11) NOT NULL DEFAULT '0' COMMENT '最高积分',
    `balance`          decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '余额',
    `total_balance`    decimal(12, 2) NOT NULL DEFAULT '0.00' COMMENT '总余额',
    `parent_id`        int(11) NOT NULL DEFAULT '0' COMMENT '上级id',
    `is_blacklist`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否黑名单',
    `contact_way`      varchar(255)   NOT NULL DEFAULT '' COMMENT '联系方式',
    `remark`           varchar(255)   NOT NULL DEFAULT '' COMMENT '备注',
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    `junior_at`        datetime       NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '成为下级时间',
    `platform`         varchar(255)   NOT NULL DEFAULT '' COMMENT '用户所属平台标识',
    `temp_parent_id`   int(11) NOT NULL DEFAULT '0' COMMENT '临时上级',
    `remark_name`      varchar(60)    NOT NULL DEFAULT '' COMMENT '备注名',
--     `source`           varchar(10)             DEFAULT '' COMMENT '用户来源',
    `pay_password`     varchar(255)   NOT NULL DEFAULT '' COMMENT '支付密码',
    PRIMARY KEY (`id`),
    KEY                `user_id` (`user_id`),
    KEY                `parent_id` (`parent_id`),
    KEY                `platform_user_id` (`platform_user_id`),
    KEY                `temp_parent_id` (`temp_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_user_platform
-- ----------------------------
DROP TABLE IF EXISTS `op_user_platform`;
CREATE TABLE `op_user_platform`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `user_id`     int(11) NOT NULL,
    `platform`    varchar(35)  NOT NULL DEFAULT '' COMMENT '用户所属平台标识',
    `platform_id` varchar(255) NOT NULL DEFAULT '' COMMENT '用户所属平台的用户id',
    `password`    varchar(255) NOT NULL DEFAULT '' COMMENT 'h5平台使用的密码',
    `unionid`     varchar(255) NOT NULL DEFAULT '' COMMENT '微信平台使用的unionid',
    `subscribe`   tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信平台使用的是否关注',
    PRIMARY KEY (`id`),
    KEY           `platform` (`platform`),
    KEY           `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_video
-- ----------------------------
DROP TABLE IF EXISTS `op_video`;
CREATE TABLE `op_video`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `title`      varchar(255)  NOT NULL COMMENT '标题',
    `type`       tinyint(1) NOT NULL COMMENT '视频来源 0--源地址 1--腾讯视频',
    `url`        varchar(2048) NOT NULL DEFAULT '' COMMENT '链接',
    `pic_url`    varchar(255)  NOT NULL COMMENT '封面图',
    `content`    longtext      NOT NULL COMMENT '详情介绍',
    `sort`       int(11) NOT NULL DEFAULT '1' COMMENT '排序',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at` timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp     NOT NULL,
    `deleted_at` timestamp     NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_video_number
-- ----------------------------
DROP TABLE IF EXISTS `op_video_number`;
CREATE TABLE `op_video_number`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) DEFAULT '0',
    `mch_id`           int(11) DEFAULT '0',
    `user_id`          int(11) DEFAULT '0',
    `goods_id`         int(11) DEFAULT '0',
    `media_id`         varchar(255) DEFAULT NULL,
    `msg_id`           varchar(255) DEFAULT NULL,
    `status`           varchar(255) DEFAULT NULL,
    `extra_attributes` text,
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_video_number_data
-- ----------------------------
DROP TABLE IF EXISTS `op_video_number_data`;
CREATE TABLE `op_video_number_data`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) DEFAULT '0',
    `mch_id`     int(11) DEFAULT '0',
    `type`       varchar(255) DEFAULT NULL,
    `key`        varchar(255) DEFAULT NULL,
    `value`      varchar(255) DEFAULT NULL,
    `url`        varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  int(11) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_vip_card
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card`;
CREATE TABLE `op_vip_card`
(
    `id`               int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) NOT NULL,
    `name`             varchar(255)   NOT NULL DEFAULT '' COMMENT '会员卡名称',
    `cover`            varchar(2048)  NOT NULL DEFAULT '' COMMENT '卡片样式',
    `type`             tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:指定商品类别 1:指定商品 2:全场通用',
    `type_info`        varchar(2048)  NOT NULL DEFAULT '',
    `discount`         decimal(11, 1) NOT NULL DEFAULT '0.0' COMMENT '折扣',
    `is_discount`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:关闭 1开启',
    `is_free_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不包邮 1:包邮',
    `status`           tinyint(1) NOT NULL DEFAULT '0',
    `created_at`       timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       timestamp      NOT NULL,
    `deleted_at`       timestamp      NOT NULL,
    `is_delete`        tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `mall_id` (`mall_id`),
    KEY                `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_vip_card_appoint_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_appoint_goods`;
CREATE TABLE `op_vip_card_appoint_goods`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`   int(11) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_vip_card_cards
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_cards`;
CREATE TABLE `op_vip_card_cards`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `detail_id` int(11) NOT NULL COMMENT 'vip卡id',
    `card_id`   int(11) NOT NULL COMMENT '卡券id',
    `send_num`  int(11) NOT NULL COMMENT '赠送数量',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_vip_card_coupons
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_coupons`;
CREATE TABLE `op_vip_card_coupons`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `detail_id` int(11) NOT NULL,
    `coupon_id` int(11) NOT NULL,
    `send_num`  int(11) NOT NULL COMMENT '赠送数量',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_vip_card_detail
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_detail`;
CREATE TABLE `op_vip_card_detail`
(
    `id`                 int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `vip_id`             int(11) NOT NULL,
    `name`               varchar(255)   NOT NULL COMMENT '标题',
    `cover`              varchar(2048)  NOT NULL DEFAULT '' COMMENT '子卡封面',
    `expire_day`         int(11) NOT NULL,
    `price`              decimal(10, 2) NOT NULL,
    `num`                int(11) NOT NULL DEFAULT '0' COMMENT '库存',
    `sort`               int(11) NOT NULL DEFAULT '100' COMMENT '排序',
    `send_integral_num`  int(11) NOT NULL DEFAULT '0' COMMENT '积分赠送',
    `send_integral_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '积分赠送类型 1.固定值|2.百分比',
    `send_balance`       decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '赠送余额',
    `title`              varchar(255)   NOT NULL DEFAULT '' COMMENT '使用说明',
    `content`            varchar(2048)  NOT NULL DEFAULT '' COMMENT '使用内容',
    `status`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常 1：停发',
    `created_at`         timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp      NOT NULL,
    `deleted_at`         timestamp      NOT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_vip_card_discount
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_discount`;
CREATE TABLE `op_vip_card_discount`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id`        int(11) NOT NULL,
    `order_detail_id` int(11) NOT NULL,
    `main_id`         int(11) NOT NULL DEFAULT '0',
    `main_name`       varchar(255)   NOT NULL DEFAULT '' COMMENT '主卡名称',
    `detail_id`       int(11) NOT NULL DEFAULT '0',
    `detail_name`     varchar(255)   NOT NULL DEFAULT '' COMMENT '子卡名称',
    `discount_num`    decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
    `discount`        decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '折扣优惠',
    `created_at`      timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY               `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_vip_card_order
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_order`;
CREATE TABLE `op_vip_card_order`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`      int(11) NOT NULL,
    `order_id`     int(11) NOT NULL,
    `user_id`      int(11) NOT NULL,
    `main_id`      int(11) NOT NULL COMMENT '主卡id',
    `main_name`    varchar(255)   NOT NULL DEFAULT '' COMMENT '主卡名称',
    `detail_id`    int(11) NOT NULL,
    `detail_name`  varchar(255)   NOT NULL DEFAULT '' COMMENT '子卡名称',
    `price`        decimal(10, 2) NOT NULL COMMENT '购买价格',
    `expire`       int(11) NOT NULL COMMENT '有效期',
    `status`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未售 1已售',
    `all_send`     varchar(4096)  NOT NULL DEFAULT '',
    `is_admin_add` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否后台添加',
    `created_at`   timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `updated_at`   timestamp NULL DEFAULT NULL,
    `deleted_at`   timestamp NULL DEFAULT NULL,
    `sign`         varchar(255)   NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY            `mall_id` (`mall_id`),
    KEY            `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_vip_card_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_setting`;
CREATE TABLE `op_vip_card_setting`
(
    `id`                      int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                 int(11) NOT NULL,
    `is_vip_card`             tinyint(1) NOT NULL DEFAULT '0',
    `payment_type`            text           NOT NULL,
    `is_share`                tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启分销',
    `is_sms`                  tinyint(1) NOT NULL DEFAULT '0',
    `is_mail`                 tinyint(1) NOT NULL DEFAULT '0',
    `is_agreement`            tinyint(1) NOT NULL DEFAULT '0',
    `agreement_title`         varchar(255)   NOT NULL DEFAULT '',
    `agreement_content`       text           NOT NULL,
    `is_buy_become_share`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '购买成为分销商 0:关闭 1开启',
    `share_type`              tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.百分比|2.固定金额',
    `share_commission_first`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `share_commission_second` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `share_commission_third`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `form`                    text           NOT NULL,
    `rules`                   text           NOT NULL COMMENT '允许的插件',
    `is_order_form`           tinyint(1) NOT NULL DEFAULT '0' COMMENT '下单表单开关',
    `order_form`              text,
    `created_at`              timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`              timestamp      NOT NULL,
    `deleted_at`              timestamp      NOT NULL,
    `is_delete`               tinyint(1) NOT NULL DEFAULT '0',
    `share_level`             text COMMENT '分销等级',
    PRIMARY KEY (`id`),
    KEY                       `mall_id` (`mall_id`),
    KEY                       `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_vip_card_user
-- ----------------------------
DROP TABLE IF EXISTS `op_vip_card_user`;
CREATE TABLE `op_vip_card_user`
(
    `id`                     int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                int(11) NOT NULL,
    `user_id`                int(11) NOT NULL,
    `main_id`                int(11) NOT NULL DEFAULT '0',
    `detail_id`              int(11) NOT NULL,
    `image_type`             tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:指定商品类别 1:指定商品 2:全场通用',
    `image_type_info`        varchar(2048)  NOT NULL DEFAULT '',
    `image_discount`         decimal(11, 1) NOT NULL DEFAULT '0.0' COMMENT '折扣',
    `image_is_free_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不包邮 1:包邮',
    `image_main_name`        varchar(255)   NOT NULL DEFAULT '' COMMENT '主卡名称',
    `image_name`             varchar(255)   NOT NULL COMMENT '名称',
    `all_send`               longtext       NOT NULL COMMENT '所有赠送信息',
    `data`                   longtext COMMENT '额外信息字段',
    `start_time`             timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `end_time`               timestamp      NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`             timestamp      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`             timestamp      NOT NULL,
    `deleted_at`             timestamp      NOT NULL,
    PRIMARY KEY (`id`),
    KEY                      `mall_id` (`mall_id`) USING BTREE,
    KEY                      `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_we7_app
-- ----------------------------
DROP TABLE IF EXISTS `op_we7_app`;
CREATE TABLE `op_we7_app`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `acid`      int(11) NOT NULL COMMENT '应用的acid',
    `is_delete` smallint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_config
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_config`;
CREATE TABLE `op_wechat_config`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `appid`      varchar(255) NOT NULL DEFAULT '',
    `appsecret`  varchar(255) NOT NULL DEFAULT '',
    `is_delete`  tinyint(4) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `name`       varchar(255) NOT NULL DEFAULT '',
    `logo`       varchar(255) NOT NULL DEFAULT '',
    `qrcode`     varchar(255) NOT NULL DEFAULT '',
    `version`    tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据版本1--第一版 2--第二版',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_template
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_template`;
CREATE TABLE `op_wechat_template`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(65)  NOT NULL DEFAULT '',
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_wxmpprograms
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_wxmpprograms`;
CREATE TABLE `op_wechat_wxmpprograms`
(
    `id`                       int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `mall_id`                  int(11) NOT NULL COMMENT '商城id',
    `nick_name`                varchar(45)            DEFAULT '' COMMENT '公众号名称',
    `token`                    varchar(45)   NOT NULL DEFAULT '' COMMENT '平台生成的token值',
    `head_img`                 varchar(255)  NOT NULL DEFAULT '' COMMENT '公众号头像',
    `verify_type_info`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方认证类型，-1代表未认证，0代表微信认证',
    `is_show`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0显示，1隐藏',
    `user_name`                varchar(45)   NOT NULL DEFAULT '' COMMENT '原始ID',
    `qrcode_url`               varchar(2048) NOT NULL DEFAULT '' COMMENT '二维码图片的URL',
    `business_info`            varchar(2048) NOT NULL DEFAULT '' COMMENT 'json格式。用以了解以下功能的开通状况（0代表未开通，1代表已开通）： open_store:是否开通微信门店功能 open_scan:是否开通微信扫商品功能 open_pay:是否开通微信支付功能 open_card:是否开通微信卡券功能 open_shake:是否开通微信摇一摇功能',
    `idc`                      int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'idc',
    `principal_name`           varchar(45)   NOT NULL DEFAULT '' COMMENT '公众号的主体名称',
    `signature`                varchar(255)  NOT NULL DEFAULT '' COMMENT '帐号介绍',
    `miniprograminfo`          varchar(255)  NOT NULL DEFAULT '' COMMENT 'json格式。判断是否为小程序类型授权，包含network小程序已设置的各个服务器域名',
    `func_info`                longtext COMMENT 'json格式。权限集列表，ID为17到19时分别代表： 17.帐号管理权限 18.开发管理权限 19.客服消息管理权限 请注意： 1）该字段的返回不会考虑小程序是否具备该权限集的权限（因为可能部分具备）。',
    `authorizer_appid`         varchar(45)   NOT NULL DEFAULT '' COMMENT '公众号appid',
    `authorizer_access_token`  varchar(255)  NOT NULL DEFAULT '' COMMENT '授权方接口调用凭据（在授权的公众号或小程序具备API权限时，才有此返回值），也简称为令牌',
    `authorizer_expires`       int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refresh有效期',
    `authorizer_refresh_token` varchar(255)  NOT NULL DEFAULT '' COMMENT '接口调用凭据刷新令牌',
    `created_at`               timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '授权时间',
    `updated_at`               timestamp     NOT NULL,
    `deleted_at`               timestamp     NOT NULL,
    `is_delete`                tinyint(1) NOT NULL DEFAULT '0',
    `version`                  tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据版本1--第一版 2--第二版',
    PRIMARY KEY (`id`),
    KEY                        `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='公众号授权列表';

-- ----------------------------
-- Table structure for op_wholesale_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_wholesale_goods`;
CREATE TABLE `op_wholesale_goods`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `goods_id`        int(11) NOT NULL,
    `mall_id`         int(11) NOT NULL,
    `type`            tinyint(1) NOT NULL COMMENT '优惠方式，0折扣，1减钱\r\n',
    `wholesale_rules` varchar(2048) NOT NULL DEFAULT '' COMMENT '批发规则',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    `rise_num`        int(11) NOT NULL DEFAULT '0' COMMENT '0不设置',
    `rules_status`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '规则开关，0关闭，1开启',
    PRIMARY KEY (`id`),
    KEY               `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='批发商品';

-- ----------------------------
-- Table structure for op_wholesale_order
-- ----------------------------
DROP TABLE IF EXISTS `op_wholesale_order`;
CREATE TABLE `op_wholesale_order`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`  int(11) NOT NULL DEFAULT '0',
    `order_id` int(11) NOT NULL DEFAULT '0',
    `discount` decimal(10, 2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY        `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wxapp_config
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_config`;
CREATE TABLE `op_wxapp_config`
(
    `id`                 int(10) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL,
    `appid`              varchar(128)  NOT NULL DEFAULT '',
    `appsecret`          varchar(255)  NOT NULL DEFAULT '',
    `created_at`         timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         timestamp     NOT NULL,
    `mchid`              varchar(32)   NOT NULL DEFAULT '',
    `key`                varchar(32)   NOT NULL DEFAULT '',
    `cert_pem`           varchar(2000) NOT NULL DEFAULT '',
    `key_pem`            varchar(2000) NOT NULL DEFAULT '',
    `wx_mini_upload_key` text COMMENT '小程序上传密钥',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wxapp_fast_create
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_fast_create`;
CREATE TABLE `op_wxapp_fast_create`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`              int(11) NOT NULL,
    `name`                 varchar(255) NOT NULL COMMENT '企业名称',
    `code`                 varchar(512) NOT NULL COMMENT '企业代码',
    `code_type`            tinyint(1) NOT NULL DEFAULT '1' COMMENT '企业代码类型（1：统一社会信用代码， 2：组织机构代码，3：营业执照注册号）',
    `legal_persona_wechat` varchar(255) NOT NULL COMMENT '法人微信',
    `legal_persona_name`   varchar(255) NOT NULL COMMENT '法人姓名',
    `component_phone`      varchar(255) NOT NULL COMMENT '第三方联系电话',
    `md5`                  varchar(255) NOT NULL COMMENT '唯一标识',
    `status`               int(11) NOT NULL DEFAULT '-2',
    `appid`                varchar(255) NOT NULL DEFAULT '' COMMENT '创建小程序appid',
    `auth_code`            varchar(512) NOT NULL DEFAULT '' COMMENT '第三方授权码',
    `updated_at`           timestamp    NOT NULL,
    `created_at`           timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`           timestamp    NOT NULL,
    `is_delete`            tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                    `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_wxapp_jump_appid
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_jump_appid`;
CREATE TABLE `op_wxapp_jump_appid`
(
    `id`      int(11) NOT NULL AUTO_INCREMENT,
    `mall_id` int(11) NOT NULL,
    `appid`   varchar(64) NOT NULL,
    PRIMARY KEY (`id`),
    KEY       `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wxapp_platform
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_platform`;
CREATE TABLE `op_wxapp_platform`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT,
    `appid`                  varchar(128) NOT NULL COMMENT '第三方平台应用appid',
    `appsecret`              varchar(255) NOT NULL COMMENT '第三方平台应用appsecret',
    `token`                  varchar(255) NOT NULL COMMENT '第三方平台应用token（消息校验Token）',
    `encoding_aes_key`       varchar(512) NOT NULL COMMENT '第三方平台应用Key（消息加解密Key）',
    `component_access_token` varchar(255) NOT NULL DEFAULT '',
    `token_expires`          int(11) NOT NULL DEFAULT '0' COMMENT 'token过期时间',
    `type`                   tinyint(1) NOT NULL DEFAULT '2' COMMENT '授权类型\r\n1：公众号\r\n2：小程序\r\n3：公众号/小程序同时展现\r\n',
--     `third_appid`            varchar(128) NOT NULL COMMENT '第三方平台绑定的应用appid',
    `domain`                 longtext     NOT NULL COMMENT '域名',
    `created_at`             timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`             timestamp    NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_wxapp_service
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_service`;
CREATE TABLE `op_wxapp_service`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `cid`        int(11) NOT NULL COMMENT 'wxapp_config',
    `appid`      varchar(128)  NOT NULL COMMENT '服务商appid',
    `mchid`      varchar(32)   NOT NULL COMMENT '服务商mchid',
    `is_choise`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '1选中  0不选',
    `created_at` timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp     NOT NULL,
    `key`        varchar(32)   NOT NULL COMMENT '服务商微信支付Api密钥',
    `cert_pem`   varchar(2000) NOT NULL DEFAULT '',
    `key_pem`    varchar(2000) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_wxapp_subscribe
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_subscribe`;
CREATE TABLE `op_wxapp_subscribe`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(65)  NOT NULL DEFAULT '',
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信订阅消息';

-- ----------------------------
-- Table structure for op_wxapp_template
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_template`;
CREATE TABLE `op_wxapp_template`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `tpl_name`   varchar(65)  NOT NULL DEFAULT '',
    `tpl_id`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wxapp_wxminiprograms
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_wxminiprograms`;
CREATE TABLE `op_wxapp_wxminiprograms`
(
    `id`                       int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `mall_id`                  int(11) NOT NULL COMMENT '商城id',
    `nick_name`                varchar(45)   NOT NULL DEFAULT '' COMMENT '微信小程序名称',
    `token`                    varchar(45)   NOT NULL DEFAULT '' COMMENT '平台生成的token值',
    `head_img`                 varchar(255)  NOT NULL DEFAULT '' COMMENT '微信小程序头像',
    `verify_type_info`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '授权方认证类型，-1代表未认证，0代表微信认证',
    `is_show`                  tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0显示，1隐藏',
    `user_name`                varchar(45)   NOT NULL DEFAULT '' COMMENT '原始ID',
    `qrcode_url`               varchar(2048) NOT NULL DEFAULT '' COMMENT '二维码图片的URL',
    `business_info`            varchar(2048) NOT NULL DEFAULT '' COMMENT 'json格式。用以了解以下功能的开通状况（0代表未开通，1代表已开通）： open_store:是否开通微信门店功能 open_scan:是否开通微信扫商品功能 open_pay:是否开通微信支付功能 open_card:是否开通微信卡券功能 open_shake:是否开通微信摇一摇功能',
    `idc`                      int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'idc',
    `principal_name`           varchar(45)   NOT NULL DEFAULT '' COMMENT '小程序的主体名称',
    `signature`                varchar(255)  NOT NULL DEFAULT '' COMMENT '帐号介绍',
    `miniprograminfo`          varchar(255)  NOT NULL DEFAULT '' COMMENT 'json格式。判断是否为小程序类型授权，包含network小程序已设置的各个服务器域名',
    `func_info`                longtext COMMENT 'json格式。权限集列表，ID为17到19时分别代表： 17.帐号管理权限 18.开发管理权限 19.客服消息管理权限 请注意： 1）该字段的返回不会考虑小程序是否具备该权限集的权限（因为可能部分具备）。',
    `authorizer_appid`         varchar(45)   NOT NULL DEFAULT '' COMMENT '小程序appid',
    `authorizer_access_token`  varchar(255)  NOT NULL DEFAULT '' COMMENT '授权方接口调用凭据（在授权的公众号或小程序具备API权限时，才有此返回值），也简称为令牌',
    `authorizer_expires`       int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refresh有效期',
    `authorizer_refresh_token` varchar(255)  NOT NULL DEFAULT '' COMMENT '接口调用凭据刷新令牌',
    `domain`                   longtext      NOT NULL COMMENT '业务域名',
    `created_at`               timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '授权时间',
    `updated_at`               timestamp     NOT NULL,
    `deleted_at`               timestamp     NOT NULL,
    `is_delete`                tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                        `mall_id` (`mall_id`),
    KEY                        `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='微信小程序授权列表';

-- ----------------------------
-- Table structure for op_wxapp_wxminiprogram_audit
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_wxminiprogram_audit`;
CREATE TABLE `op_wxapp_wxminiprogram_audit`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `appid`       varchar(45)                     NOT NULL DEFAULT '' COMMENT '小程序appid',
    `auditid`     varchar(45)                     NOT NULL DEFAULT '' COMMENT '审核编号',
    `version`     varchar(45)                     NOT NULL DEFAULT '',
    `template_id` int(11) NOT NULL COMMENT '模板id',
    `status`      tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '审核状态，其中0为审核成功，1为审核失败，2为审核中，3已提交审核, 4已发布',
    `reason`      varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '当status=1，审核被拒绝时，返回的拒绝原因',
    `created_at`  timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提交审核时间',
    `release_at`  timestamp                       NOT NULL DEFAULT '0000-00-00 00:00:00',
    `is_delete`   tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='微信小程序提交审核的小程序';

-- ----------------------------
-- Table structure for op_invoice
-- ----------------------------
DROP TABLE IF EXISTS `op_invoice`;
CREATE TABLE `op_invoice`
(
    `id`                 int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `mall_id`            int(11) NOT NULL,
    `uid`                int(11) NOT NULL COMMENT '用户id',
    `order_id`           int(11) NOT NULL COMMENT '订单ID',
    `title_type`         tinyint(1) DEFAULT NULL COMMENT '抬头类型：1：个人、政府事业单位；2：企业',
    `buyer_title`        varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '购方名称',
    `buyer_taxpayer_num` varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '购方纳税人识别号',
    `buyer_address`      varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '购方地址',
    `buyer_phone`        varchar(30) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '购方电话',
    `buyer_bank_name`    varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '购方银行名称',
    `buyer_bank_account` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '购方银行账号',
    `payee`              varchar(20) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '收款人姓名',
    `buyer_email`        varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收票人邮箱',
    `invoice_type_code`  varchar(5) COLLATE utf8_unicode_ci   DEFAULT '026' COMMENT '开具发票类型\r\n004：增值税专用发票\r\n007：增值税普通发票\r\n025：增值税卷式发票\r\n026：增值税电子普通发票\r\n028：增值税电子专用发票\r\n032：区块链电子发票',
    `medium`             tinyint(1) DEFAULT '1' COMMENT '发票介质  1电子  2纸质',
    `remarks`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '备注',
    `status`             tinyint(1) DEFAULT '0' COMMENT '状态：0：审核中；1审核成功；2开票成功；3发票撤销；4发票红冲；5审核失败；6再次提交',
    `refusal`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '拒绝原因',
    `add_time`           varchar(66) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '申请时间',
    `updated_time`       varchar(66) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '修改时间',
    `examine_time`       varchar(66) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '审核时间',
    `revoke_time`        varchar(66) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '撤销/红冲时间',
    `resubmit_time`      varchar(66) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '再次提交时间',
    `order_sn`           varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '高灯返回order_sn',
    `invoice_id`         varchar(50) COLLATE utf8_unicode_ci  DEFAULT NULL COMMENT '高灯返回invoice_id',
    `pdf_url`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `pdf_img`            varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY                  `order_id` (`order_id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发票管理';

-- ----------------------------
-- Table structure for op_invoice_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_invoice_setting`;
CREATE TABLE `op_invoice_setting`
(
    `id`                  tinyint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `mall_id`             int(11) NOT NULL,
    `switch`              tinyint(1) NOT NULL DEFAULT '0' COMMENT '开票开关',
    `tax_code`            varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '税收商品编码',
    `tax_rate`            varchar(6) COLLATE utf8_unicode_ci  NOT NULL DEFAULT '0.06' COMMENT '商品税率',
    `appkey`              varchar(50) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT 'appkey',
    `secretKey`           varchar(60) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT 'secretKey',
    `seller_taxpayer_num` varchar(20) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT '销方纳税人识别号',
    `terminal_code`       varchar(12) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT '税盘号',
    `seller_name`         varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL COMMENT '销方名称',
    `seller_address`      varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL COMMENT '销方地址',
    `seller_tel`          varchar(30) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT '销方电话',
    `seller_bank_name`    varchar(100) COLLATE utf8_unicode_ci         DEFAULT NULL COMMENT '销方银行名称',
    `seller_bank_account` varchar(50) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT '销方银行账号',
    `drawer`              varchar(16) COLLATE utf8_unicode_ci          DEFAULT NULL COMMENT '开票人姓名',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发票配置';

-- ----------------------------
-- Table structure for op_minishop_brand
-- ----------------------------
DROP TABLE IF EXISTS `op_minishop_brand`;
CREATE TABLE `op_minishop_brand`
(
    `id`                                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                            int(11) NOT NULL DEFAULT '0',
    `brand_id`                           varchar(255) NOT NULL DEFAULT '',
    `status`                             tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态 0：审核中，1：审核成功，9：审核拒绝',
    `license`                            longtext     NOT NULL COMMENT '营业执照',
    `brand_audit_type`                   tinyint(1) NOT NULL DEFAULT '1' COMMENT '认证审核类型1--国内品牌申请-R标 2--国内品牌申请-TM标 3--海外品牌申请-R标 4--海外品牌申请-TM标',
    `trademark_type`                     varchar(255) NOT NULL DEFAULT '' COMMENT '商标分类1～45',
    `brand_management_type`              tinyint(1) NOT NULL DEFAULT '1' COMMENT '经营类型 1--自有品牌 2--代理品牌 3--无品牌',
    `commodity_origin_type`              tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否进口 1--是 2--否',
    `brand_wording`                      varchar(255) NOT NULL DEFAULT '' COMMENT '商标/品牌词',
    `sale_authorization`                 longtext COMMENT '销售授权书',
    `trademark_registration_certificate` longtext COMMENT '商标注册证书',
    `trademark_registrant`               varchar(255) NOT NULL DEFAULT '' COMMENT '商标注册人姓名',
    `trademark_registrant_nu`            varchar(255) NOT NULL DEFAULT '' COMMENT '商标注册号/申请号',
    `trademark_authorization_period`     timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '商标有效期',
    `trademark_registration_application` longtext COMMENT '商标注册申请受理通知书',
    `trademark_applicant`                varchar(255) NOT NULL DEFAULT '' COMMENT '商标申请人姓名',
    `trademark_application_time`         timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '商标申请时间',
    `created_at`                         timestamp    NOT NULL,
    `updated_at`                         timestamp NULL DEFAULT NULL,
    `deleted_at`                         timestamp NULL DEFAULT NULL,
    `is_delete`                          tinyint(1) NOT NULL DEFAULT '0',
    `audit_id`                           varchar(255) NOT NULL DEFAULT '' COMMENT '审核单id',
    `reject_reason`                      varchar(255)          DEFAULT '' COMMENT '审核结果',
    PRIMARY KEY (`id`),
    KEY                                  `mall_id` (`mall_id`),
    KEY                                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_minishop_cat
-- ----------------------------
DROP TABLE IF EXISTS `op_minishop_cat`;
CREATE TABLE `op_minishop_cat`
(
    `id`             int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`        int(11) NOT NULL,
    `third_cat_id`   int(11) NOT NULL,
    `license`        longtext     NOT NULL,
    `certificate`    longtext     NOT NULL,
    `status`         tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态, 0：审核中，1：审核成功，9：审核拒绝',
    `audit_id`       varchar(255) NOT NULL DEFAULT '' COMMENT '审核单id',
    `reject_reason`  varchar(255) NOT NULL DEFAULT '' COMMENT '如果审核拒绝，返回拒绝原因',
    `created_at`     timestamp    NOT NULL,
    `updated_at`     timestamp NULL DEFAULT NULL,
    `deleted_at`     timestamp NULL DEFAULT NULL,
    `is_delete`      tinyint(1) NOT NULL DEFAULT '0',
    `third_cat_name` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY              `mall_id` (`mall_id`),
    KEY              `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_minishop_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_minishop_goods`;
CREATE TABLE `op_minishop_goods`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL DEFAULT '0',
    `goods_id`           int(11) NOT NULL DEFAULT '0',
    `apply_status`       smallint(1) NOT NULL DEFAULT '0' COMMENT '审核状态0--未审核 1--审核中 2--审核通过 3--审核驳回',
    `status`             smallint(1) NOT NULL DEFAULT '0' COMMENT '上下架状态 0--下架 1--上架 2--上架申请中',
    `product_id`         int(11) NOT NULL DEFAULT '0' COMMENT '小商店上商品id',
    `third_cat`          varchar(255)   NOT NULL DEFAULT '' COMMENT '小商店上分类',
    `brand`              varchar(255)   NOT NULL DEFAULT '' COMMENT '小商店上品牌',
    `title`              varchar(255)   NOT NULL DEFAULT '' COMMENT '小商店上商品标题',
    `price`              decimal(10, 2) NOT NULL DEFAULT '0.00' COMMENT '小商店上商品价格',
    `stock`              int(11) NOT NULL DEFAULT '0' COMMENT '小商店上商品库存',
    `goods_info`         longtext       NOT NULL COMMENT '上传时商品信息',
    `product_info`       longtext       NOT NULL COMMENT '小商店上商品信息',
    `desc`               longtext       NOT NULL COMMENT '小商店上商品详情',
    `audit_info`         longtext       NOT NULL COMMENT '审核结果',
    `created_at`         timestamp      NOT NULL,
    `updated_at`         timestamp NULL DEFAULT NULL,
    `deleted_at`         timestamp NULL DEFAULT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    `brand_id`           varchar(255)   NOT NULL DEFAULT '2100000000' COMMENT '小商店上商品品牌id',
    `qualification_pics` longtext COMMENT '小商店上商品资质图片',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_minishop_order
-- ----------------------------
DROP TABLE IF EXISTS `op_minishop_order`;
CREATE TABLE `op_minishop_order`
(
    `id`                     int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`                int(11) NOT NULL,
    `platform_order_id`      int(11) NOT NULL DEFAULT 0 COMMENT '系统订单id',
    `payment_order_union_id` int(11) NOT NULL,
    `payment_order_union_no` varchar(100)   NOT NULL,
    `order_id`               varchar(255)   NOT NULL DEFAULT '0' COMMENT '交易组件平台订单id',
    `final_price`            decimal(10, 2) NOT NULL COMMENT '订单最终价格（单位：分）',
    `status`                 int(11) NOT NULL COMMENT '订单状态10-待付款20-待发货30--待收货100--已完成200--全部商品售后之后，订单取消250--用户主动取消/待付款超时取消/商家取消1010--用户已付定金',
    `data`                   longtext       NOT NULL,
    PRIMARY KEY (`id`),
    KEY                      `mall_id` (`mall_id`),
    KEY                      `payment_order_union_id` (`payment_order_union_id`),
    KEY                      `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_minishop_refund
-- ----------------------------
DROP TABLE IF EXISTS `op_minishop_refund`;
CREATE TABLE `op_minishop_refund`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `order_id`        int(11) NOT NULL,
    `order_refund_id` int(11) NOT NULL,
    `status`          int(11) NOT NULL,
    `aftersale_id`    bigint(25) NOT NULL,
    `aftersale_infos` longtext NOT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    KEY               `mall_id` (`mall_id`),
    KEY               `order_refund_id` (`order_refund_id`),
    KEY               `aftersale_id` (`aftersale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_keyword
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_keyword`;
CREATE TABLE `op_wechat_keyword`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `rule_id`    int(11) NOT NULL DEFAULT '0' COMMENT '规则id',
    `name`       varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '匹配方式0--全匹配1--模糊匹配',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `rule_id` (`rule_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_keyword_rules
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_keyword_rules`;
CREATE TABLE `op_wechat_keyword_rules`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    `name`       varchar(255) NOT NULL DEFAULT '' COMMENT '规则名称',
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '回复方式0--全部回复1--随机一条回复',
    `reply_id`   varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wechat_subscribe_reply
-- ----------------------------
DROP TABLE IF EXISTS `op_wechat_subscribe_reply`;
CREATE TABLE `op_wechat_subscribe_reply`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `type`       int(11) NOT NULL DEFAULT '0' COMMENT '消息类型 0--文字 1--图片 2--语音 3--视频 4--图文',
    `content`    varchar(255) NOT NULL DEFAULT '' COMMENT '消息内容',
    `title`      varchar(255) NOT NULL DEFAULT '' COMMENT '图文消息时标题',
    `picurl`     longtext COMMENT '图文消息时图片链接',
    `url`        longtext COMMENT '图文消息时跳转链接，其他消息类型时媒体链接',
    `media_id`   varchar(255) NOT NULL DEFAULT '' COMMENT '图片，音频，视频消息时，临时素材id',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `status`     tinyint(1) NOT NULL DEFAULT '0' COMMENT '消息使用的地方0--关注回复1--关键词回复2--菜单回复',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_share_level_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_share_level_goods`;
CREATE TABLE `op_share_level_goods`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL default 0,
    `level_id`           int(11) NOT NULL,
    `goods_warehouse_id` int(11) NOT NULL,
    `created_at`         timestamp NULL DEFAULT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `level_id` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_mall_member_goods
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_member_goods`;
CREATE TABLE `op_mall_member_goods`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`            int(11) NOT NULL default 0,
    `member_id`          int(11) NOT NULL,
    `goods_warehouse_id` int(11) NOT NULL,
    `created_at`         timestamp NULL DEFAULT NULL,
    `is_delete`          tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                  `mall_id` (`mall_id`),
    KEY                  `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for op_app_manage
-- ----------------------------
DROP TABLE IF EXISTS `op_app_manage`;
CREATE TABLE `op_app_manage`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `name`          varchar(255)   DEFAULT NULL COMMENT '应用标识',
    `display_name`  varchar(255)   DEFAULT NULL COMMENT '应用名称',
    `pic_url_type`  int(11) DEFAULT '1' COMMENT '图标类型',
    `pic_url`       varchar(255)   DEFAULT '' COMMENT '应用图标',
    `content`       varchar(255)   DEFAULT '' COMMENT '应用简介',
    `is_show`       tinyint(1) DEFAULT '1' COMMENT '是购买用户是否可见',
    `pay_type`      varchar(255)   DEFAULT 'service' COMMENT '购买方式：online 线上购买 ',
    `price`         decimal(10, 2) DEFAULT '0.00' COMMENT '应用售价',
    `detail`        mediumtext COMMENT '应用详情',
    `created_at`    timestamp NULL DEFAULT NULL,
    `updated_at`    timestamp NULL DEFAULT NULL,
    `deleted_at`    timestamp NULL DEFAULT NULL,
    `is_delete`     tinyint(1) DEFAULT '0',
    `external_link` varchar(255)   DEFAULT NULL COMMENT '外部链接地址',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_app_order
-- ----------------------------
DROP TABLE IF EXISTS `op_app_order`;
CREATE TABLE `op_app_order`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `user_id`          int(11) DEFAULT NULL COMMENT '账号ID',
    `nickname`         varchar(255)   DEFAULT NULL COMMENT '账号名称',
    `name`             varchar(255)   DEFAULT NULL COMMENT '应用标识',
    `app_name`         varchar(255)   DEFAULT NULL COMMENT '应用名称',
    `order_no`         varchar(255)   DEFAULT NULL COMMENT '订单号',
    `pay_price`        decimal(10, 2) DEFAULT NULL COMMENT '支付价格',
    `pay_type`         varchar(255)   DEFAULT NULL COMMENT '支付方式',
    `is_pay`           tinyint(1) DEFAULT '0' COMMENT '是否支付',
    `pay_time`         timestamp NULL DEFAULT NULL COMMENT '支付时间',
    `extra_attributes` mediumtext,
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    `is_delete`        tinyint(1) DEFAULT '0',
    `out_trade_no`     varchar(255)   DEFAULT NULL COMMENT '商户订单号',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scrm_secret
-- ----------------------------
DROP TABLE IF EXISTS `op_scrm_secret`;
CREATE TABLE `op_scrm_secret`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `app_key`    varchar(255) NOT NULL DEFAULT '',
    `app_secret` varchar(255) NOT NULL DEFAULT '',
    `auth_key`   varchar(128) NOT NULL,
    `created_at` timestamp    NOT NULL,
    `updated_at` timestamp    NOT NULL,
    `deleted_at` timestamp    NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `mall_id`    int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `user_id` (`mall_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_scrm_config
-- ----------------------------
DROP TABLE IF EXISTS `op_scrm_config`;
CREATE TABLE `op_scrm_config`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `appid`     varchar(255) NOT NULL DEFAULT '',
    `secret`    varchar(255) NOT NULL DEFAULT '',
    `domain`    varchar(255) NOT NULL DEFAULT '',
    `is_delete` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY         `user_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_user_visit
-- ----------------------------
DROP TABLE IF EXISTS `op_user_visit`;
CREATE TABLE `op_user_visit`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
    `mall_id`      int(11) NOT NULL COMMENT '商城id',
    `date`         int(10) DEFAULT null COMMENT '日期',
    `visit_uv_new` int (10) DEFAULT NULL COMMENT '新增用户留存',
    `visit_uv`     int (10) DEFAULT NULL COMMENT '活跃用户留存',
    `time`         int (10) DEFAULT NULL COMMENT '时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY         `mall_id` (`mall_id`),
    KEY         `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='日用户访问';

-- ----------------------------
-- Table structure for op_url_scheme
-- ----------------------------
DROP TABLE IF EXISTS `op_url_scheme`;
CREATE TABLE `op_url_scheme`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `name`        varchar(255) NOT NULL DEFAULT '',
    `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '失效时间',
    `is_expire`   smallint(1) NOT NULL DEFAULT '0' COMMENT '生成的scheme码类型，\n到期失效：1，\n永久有效：0。',
    `link`        varchar(255) NOT NULL DEFAULT '',
    `url_scheme`  varchar(255) NOT NULL DEFAULT '',
    `is_delete`   smallint(1) NOT NULL DEFAULT '0',
    `created_at`  timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at`  timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY           `is_delete` (`is_delete`),
    KEY           `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_wxapp_trial
-- ----------------------------
DROP TABLE IF EXISTS `op_wxapp_trial`;
CREATE TABLE `op_wxapp_trial`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT,
    `type`                 tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：企业认证；2：个体认证',
    `enterprise_name`      varchar(255)          default '' COMMENT '企业名称',
    `code`                 varchar(250)          default '' COMMENT '企业代码',
    `code_type`            tinyint(1) NOT NULL DEFAULT '1' COMMENT '企业代码类型（1：统一社会信用代码， 2：组织机构代码，3：营业执照注册号）',
    `legal_persona_wechat` varchar(150)          default '' COMMENT '法人微信',
    `legal_persona_name`   varchar(150)          default '' COMMENT '法人姓名',
    `legal_persona_idcard` varchar(150)          default '' COMMENT '法人身份证',
    `component_phone`      varchar(100)          default '' COMMENT '第三方联系电话',
    `status`               int(11) NOT NULL DEFAULT '0' COMMENT '2认证成功',
    `appid`                varchar(100) NOT NULL DEFAULT '' COMMENT '创建小程序appid',
    `updated_at`           timestamp    NOT NULL,
    `created_at`           timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`           timestamp    NOT NULL,
    `is_delete`            tinyint(1) NOT NULL DEFAULT '0',
    `source`               tinyint(1) DEFAULT 0 COMMENT '来源',
    `notify_url`           varchar(350)          DEFAULT '' COMMENT '通知地址',
    `status_msg`           varchar(350)          DEFAULT '' COMMENT '状态消息',
    `wxapp_name`           varchar(250)          default '' COMMENT '小程序名称',
    `id_card_pic`          varchar(250)          default '' COMMENT '身份证正面',
    `license_pic`          varchar(250)          default '' COMMENT '营业执照',
    `wxapp_desc`           varchar(350)          default '' COMMENT '小程序简介',
    `wxapp_avatar`         varchar(250)          default '' COMMENT '小程序头像',
    `wxapp_category`       text                  default null COMMENT '小程序类目',
    PRIMARY KEY (`id`),
    KEY                    `appid` (`appid`),
    KEY                    `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='试用小程序';

-- ----------------------------
-- Table structure for op_plugin_nav
-- ----------------------------
DROP TABLE IF EXISTS `op_plugin_nav`;
CREATE TABLE `op_plugin_nav`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL COMMENT '商城id',
    `plugin_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '插件名',
    `add_time`    timestamp                           NOT NULL COMMENT '生成时间',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='顶部导航';

-- ----------------------------
-- Table structure for op_erp_order
-- ----------------------------
DROP TABLE IF EXISTS `op_erp_order`;
CREATE TABLE `op_erp_order`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL,
    `erp_no`     bigint(25) default 0 COMMENT 'erp内部单号',
    `seller_no`  varchar(250)       DEFAULT '' COMMENT '商家订单号',
    `params`     text               DEFAULT null COMMENT '参数',
    `is_cancel`  tinyint(1) DEFAULT 0 COMMENT '1:已取消',
    `is_send`    tinyint(1) DEFAULT 0 COMMENT '1:已发货',
    `updated_at` timestamp NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` timestamp NOT NULL,
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `seller_no` (`seller_no`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='erp订单记录';

-- ----------------------------
-- Table structure for op_erp_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `op_erp_order_refund`;
CREATE TABLE `op_erp_order_refund`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`     int(11) NOT NULL,
    `mch_id`      int(11) NOT NULL,
    `as_id`       bigint(25) default 0 COMMENT 'erp内部售后单号',
    `seller_no`   varchar(250)       DEFAULT '' COMMENT '商家订单号',
    `outer_as_id` varchar(250)       DEFAULT '' COMMENT '外部售后单号',
    `params`      text               DEFAULT null COMMENT '参数',
    `updated_at`  timestamp NOT NULL,
    `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at`  timestamp NOT NULL,
    `is_delete`   tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY           `mall_id` (`mall_id`),
    KEY           `seller_no` (`seller_no`),
    KEY           `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='erp订单售后记录';

-- ----------------------------
-- Table structure for op_menus_common
-- ----------------------------
DROP TABLE IF EXISTS `op_menus_common`;
CREATE TABLE `op_menus_common`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `name`       varchar(50) COLLATE utf8_unicode_ci  NOT NULL COMMENT '功能名称',
    `url`        varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '功能地址',
    `icon`       varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '功能图标',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='记录常用功能';

-- ----------------------------
-- Table structure for op_mall_no_more_notice
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_no_more_notice`;
CREATE TABLE `op_mall_no_more_notice`
(
    `id`        int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`   int(11) NOT NULL,
    `notice_id` int(11) NOT NULL COMMENT '公告id',
    `add_time`  datetime NOT NULL COMMENT '生成时间',
    PRIMARY KEY (`id`),
    KEY         `mall_id` (`mall_id`,`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='不再弹出当条公告';

-- ----------------------------
-- Table structure for op_teller_cashier
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_cashier`;
CREATE TABLE `op_teller_cashier`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `user_id`    int(11) NOT NULL COMMENT '用户ID',
    `number`     varchar(255) NOT NULL COMMENT '收银员编号',
    `store_id`   int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
    `creator_id` int(11) NOT NULL COMMENT '创建者ID',
    `status`     tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',
    `push_money` decimal(10, 2) DEFAULT '0.00' COMMENT '提成总金额',
    `sale_money` decimal(10, 2) DEFAULT '0.00' COMMENT '销售总金额',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `index` (`mall_id`,`mch_id`,`user_id`,`store_id`,`creator_id`,`status`,`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_orders
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_orders`;
CREATE TABLE `op_teller_orders`
(
    `id`                int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`           int(11) DEFAULT NULL,
    `mch_id`            int(11) DEFAULT '0',
    `order_id`          int(11) DEFAULT '0' COMMENT '订单ID',
    `re_order_id`       int(11) DEFAULT '0' COMMENT '充值订单ID',
    `cashier_id`        int(11) DEFAULT NULL COMMENT '收银员ID',
    `sales_id`          int(11) DEFAULT '0' COMMENT '导购员ID',
    `order_type`        varchar(255)   DEFAULT NULL COMMENT '订单类型',
    `add_money`         decimal(10, 2) DEFAULT '0.00' COMMENT '订单加价',
    `change_price_type` varchar(255)   DEFAULT NULL COMMENT '改价类型 加价|减价',
    `change_price`      decimal(10, 2) DEFAULT '0.00' COMMENT '订单改价金额',
    `created_at`        timestamp NULL DEFAULT NULL,
    `updated_at`        timestamp NULL DEFAULT NULL,
    `is_refund`         tinyint(1) DEFAULT '0' COMMENT '是否有退款0.否|1.是',
    `refund_money`      decimal(10, 2) DEFAULT '0.00' COMMENT '退款总金额',
    `order_query`       int(11) DEFAULT '0' COMMENT '付款码订单查询次数',
    `is_pay`            int(11) DEFAULT '0' COMMENT '是否支付0.未付款|1.已付款',
    `pay_type`          int(11) DEFAULT '0' COMMENT '支付方式',
    `work_log_id`       int(11) DEFAULT '0' COMMENT '交班记录ID',
    `is_statistics`     tinyint(1) DEFAULT '0' COMMENT '是否统计0.否|1.是',
    PRIMARY KEY (`id`),
    KEY                 `index` (`mall_id`,`mch_id`,`order_id`,`re_order_id`,`cashier_id`,`sales_id`,`order_type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_printer_setting
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_printer_setting`;
CREATE TABLE `op_teller_printer_setting`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL,
    `mch_id`          int(11) NOT NULL DEFAULT '0',
    `store_id`        int(11) DEFAULT '0',
    `printer_id`      int(11) NOT NULL COMMENT '打印机id',
    `status`          tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1启用',
    `type`            text COMMENT '打印类型',
    `order_send_type` text COMMENT '发货方式',
    `show_type`       longtext  NOT NULL COMMENT 'attr 规格 goods_no 货号 form_data 下单表单',
    `big`             int(11) NOT NULL DEFAULT '0' COMMENT '倍数',
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
    `created_at`      timestamp NOT NULL,
    `updated_at`      timestamp NOT NULL,
    `deleted_at`      timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY               `mch_id` (`mch_id`),
    KEY               `is_delete` (`is_delete`),
    KEY               `mall_id` (`mall_id`),
    KEY               `status` (`status`),
    KEY               `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_push_order
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_push_order`;
CREATE TABLE `op_teller_push_order`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) DEFAULT NULL,
    `mch_id`           int(11) DEFAULT '0',
    `user_type`        varchar(65)    DEFAULT NULL COMMENT '用户类型',
    `order_type`       varchar(65)    DEFAULT NULL COMMENT '订单类型',
    `teller_order_id`  int(11) NOT NULL COMMENT '收银台订单ID',
    `order_id`         int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
    `re_order_id`      int(11) DEFAULT '0' COMMENT '充值订单ID',
    `push_type`        varchar(65)    DEFAULT NULL COMMENT '提成类型',
    `push_order_money` decimal(10, 2) DEFAULT '0.00' COMMENT '按订单提成金额 ',
    `push_percent`     decimal(10, 2) DEFAULT '0.00' COMMENT '按百分比提成',
    `sales_id`         int(11) DEFAULT '0' COMMENT '导购员ID',
    `cashier_id`       int(11) DEFAULT '0' COMMENT '收银员ID',
    `push_money`       decimal(10, 2) DEFAULT '0.00' COMMENT '订单过售后最终提成金额',
    `status`           varchar(255)   DEFAULT NULL COMMENT '订单状态',
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    `is_delete`        tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `index` (`mall_id`,`mch_id`,`user_type`,`order_type`,`order_id`,`re_order_id`,`push_type`,`sales_id`,`cashier_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_sales
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_sales`;
CREATE TABLE `op_teller_sales`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL,
    `mch_id`     int(11) NOT NULL DEFAULT '0',
    `number`     varchar(255) NOT NULL COMMENT '导购员编号',
    `name`       varchar(255) NOT NULL COMMENT '姓名',
    `head_url`   varchar(255) NOT NULL COMMENT '头像',
    `mobile`     varchar(255) NOT NULL COMMENT '电话',
    `store_id`   int(11) NOT NULL DEFAULT '0' COMMENT '门店ID',
    `creator_id` int(11) NOT NULL COMMENT '创建者ID',
    `status`     tinyint(1) DEFAULT '0' COMMENT '状态0.不启用|1.启用',
    `push_money` decimal(10, 2) DEFAULT '0.00' COMMENT '提成总金额',
    `sale_money` decimal(10, 2) DEFAULT '0.00' COMMENT '销售总金额',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    `is_delete`  tinyint(1) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY          `index` (`mall_id`,`mch_id`,`store_id`,`creator_id`,`status`,`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_teller_work_log
-- ----------------------------
DROP TABLE IF EXISTS `op_teller_work_log`;
CREATE TABLE `op_teller_work_log`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`          int(11) DEFAULT NULL,
    `mch_id`           int(11) DEFAULT '0',
    `store_id`         int(11) DEFAULT '0' COMMENT '门店ID',
    `start_time`       timestamp NULL DEFAULT NULL COMMENT '上班时间',
    `end_time`         timestamp NULL DEFAULT NULL COMMENT '交班时间',
    `cashier_id`       int(11) DEFAULT NULL COMMENT '收银员ID',
    `status`           varchar(255) DEFAULT NULL COMMENT '交班状态pending 上班中|finish 交班完成',
    `extra_attributes` text COMMENT '交班详细信息',
    `created_at`       timestamp NULL DEFAULT NULL,
    `updated_at`       timestamp NULL DEFAULT NULL,
    `deleted_at`       timestamp NULL DEFAULT NULL,
    `is_delete`        int(11) DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY                `index` (`mall_id`,`mch_id`,`store_id`,`cashier_id`,`status`,`is_delete`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_core_file
-- ----------------------------
DROP TABLE IF EXISTS `op_core_file`;
CREATE TABLE `op_core_file`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) DEFAULT '0',
    `mch_id`     int(11) DEFAULT '0',
    `file_name`  varchar(255)   DEFAULT '' COMMENT '文件名称',
    `percent`    decimal(11, 2) DEFAULT '0.00' COMMENT '下载进度',
    `status`     tinyint(1) DEFAULT '0' COMMENT '是否完成',
    `is_delete`  tinyint(1) DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for op_page_intro
-- ----------------------------
DROP TABLE IF EXISTS `op_page_intro`;
CREATE TABLE `op_page_intro`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL COMMENT '商城',
    `route`      varchar(250) NOT NULL COMMENT '页面路径',
--     `super_content`  longtext              DEFAULT NULL COMMENT '超管员介绍内容',
--     `manage_content` longtext              DEFAULT NULL COMMENT '管理员介绍内容',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    `content`    longtext DEFAULT NULL COMMENT '介绍内容',
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='页面介绍';

-- ----------------------------
-- Table structure for op_wlhulian_data
-- ----------------------------
DROP TABLE IF EXISTS `op_wlhulian_data`;
CREATE TABLE `op_wlhulian_data`
(
    `id`                     int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`                int(11) NOT NULL COMMENT '商城',
    `shop_id`                varchar(150)       DEFAULT '' COMMENT '店铺id',
    `balance`                decimal(10, 2)     DEFAULT '0.00' COMMENT '余额',
    `price_type`             tinyint(1) default 1 COMMENT '价格类型；1：固定金额；2：百分比',
    `price_value`            decimal(8, 2)      default '0.00' COMMENT '价格',
    `is_delete`              tinyint(1) NOT NULL DEFAULT '0',
    `created_at`             timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`             timestamp NOT NULL,
    `delivery_supplier_list` varchar(400)       DEFAULT '' COMMENT '运力集合',
    `industry_type`          tinyint(1) DEFAULT '9' COMMENT '行业类型',
    PRIMARY KEY (`id`),
    KEY                      `mall_id` (`mall_id`),
    KEY                      `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商城聚合配送数据';

-- ----------------------------
-- Table structure for op_wlhulian_wallet_log
-- ----------------------------
DROP TABLE IF EXISTS `op_wlhulian_wallet_log`;
CREATE TABLE `op_wlhulian_wallet_log`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`    int(11) NOT NULL COMMENT '商城',
    `user_id`    int(11) NOT NULL COMMENT '操作的用户id',
    `order_no`   varchar(150)       DEFAULT '' COMMENT '订单号',
    `money`      decimal(8, 2)      DEFAULT '0.00' COMMENT '操作金额',
    `type`       tinyint(1) default 1 COMMENT '类型；1：充值；2：扣除',
    `balance`    decimal(10, 2)     DEFAULT '0.00' COMMENT '商城余额',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `mall_id` (`mall_id`),
    KEY          `order_no` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='聚合配送钱包记录';

-- ----------------------------
-- Table structure for op_attachment_effect
-- ----------------------------
DROP TABLE IF EXISTS `op_attachment_effect`;
CREATE TABLE `op_attachment_effect`
(
    `id`         int(11) unsigned NOT NULL AUTO_INCREMENT,
    `pic_id`     int(11) NOT NULL COMMENT '图片id',
    `effect_id`  int(11) NOT NULL COMMENT '效果图id',
    `tag` varchar(30) DEFAULT NULL COMMENT '定位标签',
    `is_delete`  tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY          `pic_id` (`pic_id`),
    KEY          `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='图片效果列表';

-- ----------------------------
-- Table structure for op_mall_extend
-- ----------------------------
DROP TABLE IF EXISTS `op_mall_extend`;
CREATE TABLE `op_mall_extend`
(
    `id`              int(11) unsigned NOT NULL AUTO_INCREMENT,
    `mall_id`         int(11) NOT NULL COMMENT '商城',
    `goods_limit_num` int(11) DEFAULT -1 COMMENT '商品限制数量，-1代表无限制',
    `memory`          int(10) NOT NULL DEFAULT 5120 COMMENT '总内存 -1为不限制，单位M',
    `used_memory`     decimal(16, 8) NOT NULL DEFAULT 0 COMMENT '已使用内存，单位M',
    `created_at`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      timestamp NOT NULL,
    `is_delete`       tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `mall_id` (`mall_id`),
    KEY `is_delete` (`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商城扩展信息';

SET foreign_key_checks = 1;
