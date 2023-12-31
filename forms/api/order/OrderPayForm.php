<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/16 10:46
 */


namespace app\forms\api\order;


use app\models\Order;
use app\models\OrderSubmitResult;

class OrderPayForm extends OrderPayFormBase
{
    public $queue_id;
    public $token;

    public function rules()
    {
        return [
            [['queue_id', 'token'], 'required'],
        ];
    }

    public function getResponseData()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        if(isset(\Yii::$app->kafka)){
            if (!\Yii::$app->kafka->isDone($this->queue_id)) {
                return [
                    'code' => 0,
                    'data' => [
                        'retry' => 1,
                    ],
                ];
            }
        }else {
            if (!\Yii::$app->queue2->isDone($this->queue_id)) {
                return [
                    'code' => 0,
                    'data' => [
                        'retry' => 1,
                    ],
                ];
            }
        }
        /** @var Order[] $orders */
        $orders = Order::find()->where([
            'token' => $this->token,
            'is_delete' => 0,
            'user_id' => $this->getUser()->id,
        ])->all();
        if (!$orders || !count($orders)) {
            $orderSubmitResult = OrderSubmitResult::findOne([
                'token' => $this->token,
            ]);
            if ($orderSubmitResult) {
                return [
                    'code' => 1,
                    'msg' => $orderSubmitResult->data,
                ];
            }
            return [
                'code' => 1,
                'msg' => '订单不存在或已失效。',
            ];
        }
        return $this->getReturnData($orders);
    }

    public function getUser()
    {
        return \Yii::$app->user;
    }
}
