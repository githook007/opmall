<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/16 10:46
 */

namespace app\forms\pc\order;

use app\bootstrap\response\ApiCode;
use app\models\Order;
use app\models\OrderSubmitResult;

class OrderPayForm extends OrderPayBase
{
    public $queue_id;
    public $order_token;

    public function rules()
    {
        return [
            [['queue_id', 'order_token'], 'required'],
        ];
    }

    public function getResponseData()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        if (!\Yii::$app->queue->isDone($this->queue_id)) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'retry' => 1,
                ],
            ];
        }
        /** @var Order[] $orders */
        $orders = Order::find()->where([
            'token' => $this->order_token,
            'is_delete' => 0,
            'user_id' => \Yii::$app->user->id,
        ])->all();
        if (!$orders || !count($orders)) {
            $orderSubmitResult = OrderSubmitResult::findOne([
                'token' => $this->order_token,
            ]);
            if ($orderSubmitResult) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $orderSubmitResult->data,
                ];
            }
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '订单不存在或已失效。',
            ];
        }
        try {
            return $this->getReturnData($orders);
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
