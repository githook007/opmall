<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\logistics;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\forms\wlhulian\api\SupplierQuery;
use app\forms\wlhulian\ApiForm;
use app\models\Model;
use app\models\Option;
use app\models\WlhulianData;

class WlForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function getSetting()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $setting = $this->getOption();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'setting' => $setting,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }


    public function getStoreSetting()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $setting = $this->getOption();
            $store = $this->getStoreOption();
            $store['storeId'] = $setting['storeId'];

            $api = new ApiForm($setting);
            $api->object = (new SupplierQuery());
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'store' => $store,
                    'options' => $this->getIndustryType(),
                    'delivery_supplier' => $api->request()
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getPriceSetting()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $setting = $this->getPriceOption();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'setting' => $setting
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getOption()
    {
        $setting = CommonOption::get('open_wlhulian_setting', 0, Option::GROUP_ADMIN);

        return CommonAppConfig::check($setting, $this->getDefault());
    }

    public function getDefault()
    {
        return [
            'appId' =>  '',
            'secret' =>  '',
            'storeId' =>  '',
            'status' =>  '1',
            'is_prod' =>  '1',
        ];
    }

    public function getStoreOption()
    {
        $setting = CommonOption::get('open_wlhulian_store_setting', 0, Option::GROUP_ADMIN);

        return CommonAppConfig::check($setting, $this->getStoreDefault());
    }

    public function getStoreDefault()
    {
        return [
            "contactName" =>  "", //联系人姓名
            //"outShopId" =>  "", //外部方门店id（接入方门店id与平台方门店id必传一个）
            "shopName" => "", //门店名称（注：名称含无意义字符串、空格和特殊字符会导致运力审核不通过）
            "shopAddress" => "", //门店地址
            "cityName" => "", //所在城市
            "industryType" => 1, //"行业类型 1 => 餐饮 \n" +

            "deliverySupplierList" => [], //门店所选接入运力编码集合
            "shopLng" => "", //地理位置经度（目前只支持百度坐标）
            "contactPhone" => "", //联系人电话
            "shopAddressDetail" => "", //门牌号
            "shopLat" => "" //地理位置纬度（目前只支持百度坐标）
        ];
    }

    public function getPriceOption()
    {
        if(!$this->id){
            $setting = CommonOption::get('open_wlhulian_price_setting', 0, Option::GROUP_ADMIN);
            $setting = CommonAppConfig::check($setting, $this->getPriceDefault());
            foreach ($this->getPriceDefault() as $k => $v){
                if(is_integer($v)){
                    $setting[$k] = intval($setting[$k]);
                }
            }
        }else{
            $setting = [];
            $data = WlhulianData::findOne(['mall_id' => $this->id, 'is_delete' => 0]);
            foreach ($this->getPriceDefault() as $k => $value){
                try{
                    $setting[$k] = $data->$k;
                }catch (\Exception $e){
                    $setting[$k] = $value;
                }
            }
        }
        return $setting;
    }

    public function getPriceDefault()
    {
        return [
            'price_type' =>  1,
            'price_value' =>  '0',
        ];
    }

    public function getIndustryType()
    {
        return [
            ['label' => '餐饮', 'value' => 1],
            ['label' => '鲜花', 'value' => 2],
            ['label' => '蛋糕', 'value' => 3],
            ['label' => '商超', 'value' => 4],
            ['label' => '医药', 'value' => 5],
            ['label' => '母婴', 'value' => 6],
            ['label' => '服饰', 'value' => 7],
            ['label' => '数码电子', 'value' => 8],
            ['label' => '其他', 'value' => 9],
        ];
    }
}
