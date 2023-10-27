<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\supply_goods\models\SupplyGoodsWholesaler;
use app\plugins\supply_goods\models\System;

class WholesalerForm extends Model
{
    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function getInfo(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $data = SupplyGoodsWholesaler::findOne(['mall_id' => \Yii::$app->mall->id, 'user_id' => \Yii::$app->user->id]);
        $user  = \Yii::$app->user->identity;
        $ExemptionUrl = System::MCH_URL.System::$mch_conf['ExemptionLogin'].'&user_id='.base64_encode($user->id).'&username='.$user->username.'&sign='.md5(json_encode(['username' => $user->username, 'user_id' => base64_encode($user->id)]));
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $data,
            'ExemptionUrl' => $ExemptionUrl,
            'msg' => '保存成功'
        ];
    }

}
