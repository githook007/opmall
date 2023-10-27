<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\mall\wlhulian;

use app\bootstrap\response\ApiCode;
use app\forms\admin\logistics\WlForm;
use app\forms\wlhulian\api\ShopCreate;
use app\forms\wlhulian\api\ShopSupplierQuery;
use app\forms\wlhulian\api\ShopUpdate;
use app\forms\wlhulian\ApiForm;
use app\forms\wlhulian\GeoTransUtil;
use app\models\MallSetting;
use app\models\Model;

class EditForm extends Model
{
    public $quick_map_address;
    public $latitude;
    public $delivery_supplier_list;
    public $industry_type;
    public $longitude;
    public $contact_tel;

    public function rules()
    {
        return [
            [['industry_type'], 'integer'],
            [['quick_map_address', 'latitude', 'longitude', 'contact_tel'], 'string'],
            [['delivery_supplier_list'], 'safe'],
        ];
    }

    private function createShop(){
        $mall = \Yii::$app->mall;
        $model = $mall->wlHulian;

        if(!$model->shop_id) {
            $object = new ShopCreate();
            $object->outShopId = "P".date("mdHis") .rand(100, 999);
        }else{
            $object = new ShopUpdate();
            $object->outShopId = $model->shop_id;
        }
        $result = GeoTransUtil::gcj_to_bd($this->longitude, $this->latitude);

        $object->shopName = $mall->name;
        $object->contactPhone = $this->contact_tel;
        $object->contactName = $mall->name;
        $object->shopAddress = $this->quick_map_address;
        $address = explode(" ", address_handle($object->shopAddress));
        $object->cityName = $address[1] ?? '';
        $object->shopLat = $result['lat'];
        $object->shopLng = $result['lng'];
        $object->shopAddressDetail = ' ';
        $object->industryType = $this->industry_type;
        $object->deliverySupplierList = $this->delivery_supplier_list;

        $config = (new WlForm())->getOption();
        $api = (new ApiForm($config));
        $api->object = $object;
        $api->request();

        $model->shop_id = $object->outShopId;
        if(!$model->save()){
            throw new \Exception($this->getErrorMsg($model));
        }

        $object = new ShopSupplierQuery();
        $object->outShopId = $model->shop_id;
        $api->object = $object;
        return $api->request();
    }

    public function save(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = \Yii::$app->mall->wlHulian;
        if(!$model){
            throw new \Exception('不存在');
        }
        $model->industry_type = $this->industry_type;
        foreach ($this->delivery_supplier_list as &$item){
            $item = intval($item);
        }
        unset($item);
        $model->delivery_supplier_list = \Yii::$app->serializer->encode($this->delivery_supplier_list);
        if (!$model->save()) {
            throw new \Exception($this->getErrorMsg($model));
        }

        $default = \Yii::$app->mall->getDefault();
        foreach ($this->attributes as $key => $value) {
            if (isset($default[$key]) && is_array($default[$key])) {
                $value = json_encode($value);
            }
            if (isset($default[$key])) {
                $mallSetting = MallSetting::findOne(['key' => $key, 'mall_id' => \Yii::$app->mall->id]);
                if(!$mallSetting){
                    $mallSetting = new MallSetting();
                    $mallSetting->key = $key;
                    $mallSetting->mall_id = \Yii::$app->mall->id;
                }
                $mallSetting->value = (string)$value;
                $res = $mallSetting->save();
                if (!$res) {
                    throw new \Exception($this->getErrorMsg($mallSetting));
                }
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '成功',
            'data' => $this->createShop()
        ];
    }
}
