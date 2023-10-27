<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\order;

use app\bootstrap\response\ApiCode;
use app\models\Order;

class OrderRetryPayForm extends OrderPayBase
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    public function getResponseData()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        try {
            $order = Order::find()->where([
                'id' => $this->id,
                'user_id' => \Yii::$app->user->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
                'is_pay' => 0,
                'cancel_status' => 0,
                'is_confirm' => 0,
                'is_sale' => 0,
            ])->one();

            if (!$order) {
                throw new \Exception('订单数据异常,无法支付');
            }

            return $this->getReturnData([$order]);
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
