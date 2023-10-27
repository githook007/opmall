<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\goods\BaseGoodsList;
use app\forms\open_api\RequestForm;
use app\plugins\supply_goods\models\System;
use yii\helpers\Json;

class MchGoodsForm extends BaseGoodsList
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function getDetail(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $request = new RequestForm();
        $r = (\Yii::$app->request->get()['r']);
        parse_str($r, $res);
        $request->attributes = [
            'url' => System::MCH_URL . System::$mch_conf['mchGoodsDetail'],
            'data' => [
                'id' => $this->id,
                'mch_id' => $res['mch_id'],
            ],
        ];
        $response = $request->api();
        if ($response['code'] !== 0){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $response['msg'] ?? '服务器内部错误'
            ];
        }
        $data = $response['data']['detail'];
//        $data['original_price'] = $data['price'];
        if(!empty($data['mchCats'])) {
            $data['cats'] = $data['mchCats'];
        }
        \Yii::$app->cache->set("supply_goods_cats_".$this->id, Json::encode($data['cats']), 86400);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $data
            ]
        ];
    }

}
