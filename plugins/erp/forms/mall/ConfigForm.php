<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/12/18
 * Time: 3:36 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\erp\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\plugins\erp\forms\common\api\ServeHttp;
use app\plugins\erp\forms\common\RequestForm;
use app\models\Model;
use app\models\Option;

class ConfigForm extends Model
{
    public $app_key;
    public $app_secret;
    public $access_token;
    public $shop_id;
    public $status;
    public $env;

    public $keyword;

    public function rules()
    {
        return [
            [['app_key', 'app_secret', 'access_token'], 'trim'],
            [['app_key', 'app_secret', 'access_token', 'keyword', 'env'], 'string'],
            [['shop_id', 'status'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_key' => 'app_key',
            'app_secret' => 'app_secret',
            'access_token' => 'access_token',
            'keyword' => '搜索关键词',
        ];
    }

    public function getShop(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try{
            $where = [];
            if($this->keyword) {
                if (!is_numeric($this->keyword)) {
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => []
                    ];
                }
                $where['shop_ids'] = [$this->keyword];
            }
            $apiRes = RequestForm::getInstance()->api(ServeHttp::QUERY_SHOPS, $where);
            if($apiRes['code'] == 100){
                RequestForm::getInstance()->getApiObj()->refreshToken();
                $apiRes = RequestForm::getInstance()->api(ServeHttp::QUERY_SHOPS, $where);
            }
            if($apiRes['code'] != 0){
                throw new \Exception($apiRes['msg']);
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $apiRes['data']['datas']
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    public function getAuthUrl()
    {
        try {
            $setting = CommonOption::get(Option::MALL_ERP, \Yii::$app->mall->id, Option::GROUP_APP, null, \Yii::$app->user->identity->mch_id);
            $this->attributes = (array)$setting;
            if(!$this->app_key || !$this->app_secret){
                throw new \Exception('请先保存数据再获取token');
            }
            $api = RequestForm::getInstance();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => ['authUrl' => $api->createUrl()]
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $setting = CommonOption::get(Option::MALL_ERP, \Yii::$app->mall->id, Option::GROUP_APP, [], \Yii::$app->user->identity->mch_id);
        $setting = array_merge((array)$setting, $this->attributes);
        $setting = $this->checkDefault($setting, $this->getDefault());
        CommonOption::set(Option::MALL_ERP, $setting, \Yii::$app->mall->id, Option::GROUP_APP, \Yii::$app->user->identity->mch_id);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功。',
        ];
    }

    public function getDefault()
    {
        return RequestForm::getInstance()->getApiObj()->getAttributes();
    }

    public function getDetail()
    {
        $setting = CommonOption::get(Option::MALL_ERP, \Yii::$app->mall->id, Option::GROUP_APP, [], \Yii::$app->user->identity->mch_id);
        $setting = $this->checkDefault($setting, $this->getDefault());
        if(!$setting['app_key'] || !$setting['app_secret']){
            $shopErr = 1;
        }
        $shop = [];
        if($setting['shop_id']){
            $apiRes = RequestForm::getInstance($setting)->api(ServeHttp::QUERY_SHOPS, ['shop_ids' => [$setting['shop_id']]]);
            if(!empty($apiRes['data']['datas'][0])){
                $shop = $apiRes['data']['datas'][0];
            }
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $setting,
                'shop' => $shop,
                'shopErr' => $shopErr ?? '',
            ]
        ];
    }

    public function checkDefault($data, $default)
    {
        $newData = [];
        foreach ($default as $key => $item) {
            if (is_array($item)) {
                $newData[$key] = $this->checkDefault($data[$key], $item);
            }else{
                $newData[$key] = $data[$key] ?? $item;
            }
        }
        return $newData;
    }
}
