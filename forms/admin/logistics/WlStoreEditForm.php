<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\logistics;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\forms\wlhulian\api\StoreCreate;
use app\forms\wlhulian\ApiForm;
use app\forms\wlhulian\GeoTransUtil;
use app\models\Model;
use app\models\Option;

class WlStoreEditForm extends Model
{
    public $storeId;
    public $shopId;
    public $industryType;
    public $contactName;
    public $shopName;
    public $deliverySupplierList;
    public $shopAddress;
    public $longitude;
    public $latitude;
    public $contactPhone;
    public $shopAddressDetail;

    public function rules()
    {
        return [
            [['industryType'], 'integer'],
            [['contactName', 'shopName', 'shopAddress', 'longitude', 'latitude', 'shopAddressDetail', 'storeId', 'shopId'], 'string'],
            [['contactPhone'], 'number'],
            [['deliverySupplierList'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            if(!$this->storeId){
                $result = GeoTransUtil::gcj_to_bd($this->longitude, $this->latitude);
                $object= new StoreCreate();
                $object->attribute = $this->attributes;
                $object->outShopId = date("mdHis") .rand(100, 999);
                $address = explode(" ", address_handle($this->shopAddress));
                $object->cityName = $address[1] ?? '';
                $object->coordinateType = 1;
                $object->shopLat = $result['lat'];
                $object->shopLng = $result['lng'];
                $config = (new WlForm())->getOption();
                $api = new ApiForm($config);
                $api->object = $object;
                $res = $api->request();
                $this->attributes = $res;
                $config['storeId'] = $this->storeId;
                $form = new WlEditForm();
                $form->attributes = (array)$config;
                $res = $form->save();
                if($res['code'] !== 0){
                    return $res;
                }
            }
            foreach ($this->deliverySupplierList as &$item){
                $item = intval($item);
            }
            unset($item);
            CommonOption::set('open_wlhulian_store_setting', $this->attributes, 0, Option::GROUP_ADMIN);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
