<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 修改发货店铺
class ShopUpdate extends Base
{
    /** @var string 联系人姓名 */
    public $contactName;

    /** @var string 外部方门店id */
    public $outShopId;

    /** @var string 店铺名称 */
    public $shopName;

    /** @var string 店铺地址 */
    public $shopAddress;

    /** @var string 所在城市 */
    public $cityName;

    /** @var integer 行业类型 1:餐饮 2:鲜花 3:蛋糕 4:商超 5:医药 6:母婴 7:服饰 8:数码电子 9:其他 */
    public $industryType;

    /** @var array 店铺所选接入运力编码集合 */
    public $deliverySupplierList;

    /** @var string 地理位置经度（目前只支持百度坐标） */
    public $shopLng;

    /** @var string 联系人电话 */
    public $contactPhone;

    /** @var string 门牌号 */
    public $shopAddressDetail;

    /** @var string 地理位置纬度（目前只支持百度坐标） */
    public $shopLat;

    public function getAttribute(): array
    {
        return get_object_vars($this);
    }

    public function getMethodName()
    {
        return "/api/v1/shop/update";
    }
}
