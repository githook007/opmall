<?php

namespace app\forms\pc;

class SettingConf
{
    const GROUP_PC = 'pc'; // 模块名

    const STORE_NAME = 'store_name';// 商城名称
    const STORE_LOGO = 'store_logo';// 商城logo
    const COPYRIGHT = 'copyright';// 版权信息
    const ICP = 'icp';// ICP许可证
    const ICP_URL = 'icp_url';// ICP许可证地址
    const RECORD_NUMBER = 'record_number';// 备案号
    const RECORD_NUMBER_URL = 'record_number_url';// 备案地址
    const BOTTOM_PIC_LIST = 'bottom_pic_list';// 底部图片
    const FRIENDSHIP_LINK = 'friendship_link';// 友情链接
    const AD_TITLE = 'ad_title';// 广告标题
    const AD_PIC = 'ad_list';// 广告图
    const RECOMMEND_TITLE = 'recommend_title';// 推荐标题
    const RECOMMEND_CAT_LIST = 'recommend_cat_list';// 推荐分类
    const ANNOUNCEMENT_TITLE = 'announcement_title';// 公告标题
    const ANNOUNCEMENT_URL = 'announcement_url';// 公告链接地址
    const MALL_DESC = "mall_desc"; // 商家简介
    const QQ_CUSTOMER_SERVICE = "qq_customer_service"; // QQ客服
    const WEB_CUSTOMER_SERVICE = "web_service_url"; // 外链客服

    static $basicSetting = [
        self::STORE_NAME => "xx", self::STORE_LOGO => "", self::COPYRIGHT => "", self::ICP => "", self::ICP_URL => "",
        self::RECORD_NUMBER => "", self::RECORD_NUMBER_URL => "http://beian.miit.gov.cn",
        self::BOTTOM_PIC_LIST => [], self::FRIENDSHIP_LINK => [], self::RECOMMEND_TITLE => "为你推荐",
        self::RECOMMEND_CAT_LIST => [], self::ANNOUNCEMENT_TITLE => "公告来了", self::ANNOUNCEMENT_URL => "",
        self::MALL_DESC => "", self::QQ_CUSTOMER_SERVICE => [], self::WEB_CUSTOMER_SERVICE => []
    ];

    static $homeAd = [
        self::AD_TITLE => "店铺街", self::AD_PIC => [],
    ];

    static $pcHomeData = [
        self::STORE_NAME => "xx", self::STORE_LOGO => "", self::COPYRIGHT => "", self::ICP => "", self::ICP_URL => "",
        self::RECORD_NUMBER => "", self::RECORD_NUMBER_URL => "http://beian.miit.gov.cn",
        self::BOTTOM_PIC_LIST => [], self::FRIENDSHIP_LINK => [], self::QQ_CUSTOMER_SERVICE => [],
        self::WEB_CUSTOMER_SERVICE => [], self::ANNOUNCEMENT_TITLE => "公告来了", self::ANNOUNCEMENT_URL => "",
    ];

    static $recommendData = [
        self::RECOMMEND_TITLE => "为你推荐", self::RECOMMEND_CAT_LIST => []
    ];
}
