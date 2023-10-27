<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\open_api;

use app\models\Model;
use Curl\Curl;
use yii\helpers\Json;

class RequestForm extends Model
{
    public $data;
    public $url;

    public function rules()
    {
        return [
            [['data', 'url'], 'required'],
            [['data'], 'safe'],
            [['url'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'data' => '请求参数数组',
            'url' => '请求地址',
        ];
    }

    public function api(){
        if (!$this->validate()) {
            throw new \Exception($this->getErrorMsg());
        }
        $sign = DockingForm::getSign($this->data);
        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json;charset=UTF-8');
        $curl->setHeader('CommonOpen-Sign', $sign);
        $curl->setOpt(CURLOPT_TIMEOUT, 30);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = $curl->post($this->url, Json::encode($this->data));
        if($response->response){
            $res = $response->response;
        }else{
            throw new \Exception($response->error_message);
        }
        try {
            return Json::decode($res, true);
        }catch (\Exception $e){
            throw new \Exception($res);
        }
    }
}
