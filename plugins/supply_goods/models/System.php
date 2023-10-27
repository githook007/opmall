<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\models;


class System
{
    const MCH_URL = "http://127.0.0.1/mall_source"; // 货源多商户站 - 请求地址
    static $mch_conf = [
        "mchUser" => "/web/shoproot.php?r=plugin/mch/open_api/source/mch-user", // 同步用户去商户系统审核
        "ExemptionLogin" => "/web/shoproot.php?r=plugin/mch/open_api/source/exemption-login", // 同步用户去商户系统审核
        "mchGoodsList" => "/web/shoproot.php?r=plugin/mch/open_api/goods/mch-goods-list",  // 多商户商品列表
        'mchGoodsDetail' => "/web/shoproot.php?r=plugin/mch/open_api/goods/mch-goods-detail",  // 多商户商品详情
    ];
}
