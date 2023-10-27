<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

// 获取计价
class OrderBilling extends Base
{
    /** @var string 发单渠道编号（不填则为默认返回店铺全部可用的运力）， 不必填 */
    public $supplierCode;

    /** @var string 接入方平台订单号 */
    public $outOrderNo;

    /** @var string 发货门店  */
    public $outShopCode;

    /** @var string 收件地址  */
    public $toAddress;

    /** @var string 收件人详细地址  */
    public $toAddressDetail;

    /** @var string 收件经度， 目前只支持百度坐标  */
    public $toLng;

    /** @var string 收件纬度, 目前只支持百度坐标  */
    public $toLat;

    /** @var string 收件人姓名  */
    public $toReceiverName;

    /** @var string 收件人联系方式  */
    public $toMobile;

    /** @var int 物品类型 可选值：行业类型 1:餐饮,2:鲜花,3:蛋糕,4:商超,5:医药,6:母婴,7:服饰,8:数码电子,9:其他  */
    public $goodType;

    /** @var int 物品重量,单位KG  */
    public $weight;

    public function getAttribute(): array
    {
        $params = get_object_vars($this);
        foreach ($params as $k => $value){
            if(($value === '' || $value === null)){
                unset($params[$k]);
            }
        }
        return $params;
    }

    public function getMethodName()
    {
        return "/api/v1/order/billing";
    }
}
