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

class MchGoodsListForm extends BaseGoodsList
{
    public function getList(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $user  = \Yii::$app->user->identity;
        $request = new RequestForm();
        $sourceType = \Yii::$app->session['sourceType'];
        $data = $this->attributes;
        if ($sourceType == 2){
            $data = array_merge($data, [
                'username' => $user->username,
                'user_id' => base64_encode($user->id),
            ]);
        }
        $request->attributes = [
            'url' => System::MCH_URL . System::$mch_conf['mchGoodsList'],
            'data' => $data,
        ];
        $response = $request->api();
        if ($response['code'] !== 0){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $response['msg'] ?? '服务器内部错误'
            ];
        }
        return $response;
    }
}
