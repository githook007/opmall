<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\city_service;

use app\bootstrap\response\ApiCode;
use app\forms\common\city_service\BaseCityService;
use app\models\CityService;

class BaseCityServiceEdit extends BaseCityService
{
    public $id;
    public $name;
    public $distribution_corporation;
    public $appkey;
    public $appsecret;
    public $shop_no;
    public $shop_id; //商户ID
    public $service_type;
    public $product_type;
    public $wx_product_type;
    public $outer_order_source_desc;
    public $delivery_service_code;
    public $is_debug;

    public function rules()
    {
        return [
            [['name', 'distribution_corporation', 'service_type'], 'required'],
            [['id', 'distribution_corporation', 'delivery_service_code', 'is_debug'], 'integer'],
            [['appkey', 'appsecret', 'shop_no'], 'default', 'value' => ''],
            [['name', 'appkey', 'appsecret', 'shop_no', 'service_type', 'shop_id', 'product_type', 'outer_order_source_desc'], 'string'],
            [['name'], 'string', 'max' => '20'],
            [['wx_product_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '即时配送商家ID',
            'name' => '配送名称',
            'distribution_corporation' => '配送公司',
            'appkey' => 'appkey',
            'appsecret' => 'appsecret',
            'shop_no' => '门店ID',
            'shop_id' => '商户ID',
            'service_type' => '第三方平台接口',
            'product_type' => '物品类别',
            'wx_product_type' => '微信物品类别',
            'outer_order_source_desc' => '订单来源',
            'delivery_service_code' => '配送服务',
            'is_debug' => '测试模式',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $this->checkData();

            if ($this->id) {
                $cityService = CityService::find()->andWhere([
                    'id' => $this->id,
                    'mall_id' => \Yii::$app->mall->id,
                    'is_delete' => 0,
                ])->one();
                if (!$cityService) {
                    throw new \Exception('配送商家不存在');
                }
            } else {
                $cityService = new CityService();
                $cityService->mall_id = \Yii::$app->mall->id;
                $cityService->platform = $this->platform;
            }

            $cityService->name = $this->name;
            $cityService->distribution_corporation = $this->distribution_corporation;
            $cityService->shop_no = $this->shop_no;
            $cityService->service_type = $this->service_type;
            $data = [
                'appkey' => $this->appkey ?: '',
                'appsecret' => $this->appsecret ?: '',
                'shop_id' => $this->shop_id ?: '',
                'product_type' => $this->product_type ?: '',
                'wx_product_type' => $this->wx_product_type ? json_encode($this->wx_product_type, true): json_encode([]),
                'is_debug' => $this->is_debug
            ];

            // 美团配置
            if ($this->distribution_corporation == 3) {
                $data['outer_order_source_desc'] = $this->outer_order_source_desc ?: '其它';
                $data['delivery_service_code'] = $this->delivery_service_code ?: 4002;
            }

            $cityService->data = json_encode($data);
            $res = $cityService->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($cityService));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'error' => [
                    'line' => $exception->getLine(),
                ],
            ];
        }
    }

    private function checkData()
    {
        if (!in_array($this->distribution_corporation, $this->getCorporationValueList())) {
            throw new \Exception('配送公司数据异常');
        }
    }
}
