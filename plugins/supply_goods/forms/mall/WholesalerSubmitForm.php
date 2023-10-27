<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\open_api\RequestForm;
use app\models\Model;
use app\models\User;
use app\plugins\supply_goods\models\System;
use app\plugins\supply_goods\models\SupplyGoodsWholesaler;

class WholesalerSubmitForm extends Model
{
    public $name;
    public $introduction;
    public $phone;
    public $address;
    public $send_type;
    public $send_time;
    public $logo;
    public $back_image;

    public function rules()
    {
        return [
            [['name', 'phone', 'address', 'logo', 'back_image'], 'required'],
            [['name', 'introduction', 'phone', 'address', 'send_type', 'send_time', 'logo', 'back_image'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '批发商名称',
            'phone' => '联系方式',
            'address' => '地址',
            'logo' => 'logo',
            'back_image' => '背景图',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            /** @var User $user */
            $user = \Yii::$app->user->identity;

            $request = new RequestForm();
            $request->attributes = [
                'url' => System::MCH_URL . System::$mch_conf['mchUser'],
                'data' => array_merge($this->attributes, [
                    'username' => $user->username,
                    'user_id' => base64_encode($user->id),
                    'domain' => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl,
                    'shop_id' => \Yii::$app->mall->id
                ]),
            ];
            $response = $request->api();
            if ($response['code'] !== 0){
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $response['msg'] ?? '服务器内部错误'
                ];
            }

            $wholesaler = new SupplyGoodsWholesaler();
            $wholesaler->attributes = $this->attributes;
            $wholesaler->mall_id = \Yii::$app->mall->id;
            $wholesaler->user_id = \Yii::$app->user->id;
            $wholesaler->status = 0;
            $wholesaler->add_time = $wholesaler->update_time = mysql_timestamp();
            $wholesaler->is_delete = 0;
            if (!$wholesaler->save()) {
                throw new \Exception($this->getErrorMsg($wholesaler));
            }
            \Yii::$app->session->set('sourceType', 2);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}
