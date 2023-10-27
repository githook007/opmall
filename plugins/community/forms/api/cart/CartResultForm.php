<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/10
 * Time: 15:55
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\forms\api\cart;


use app\models\OrderSubmitResult;
use app\plugins\community\forms\Model;

class CartResultForm extends Model
{
    public $token;
    public $queue_id;

    public function rules()
    {
        return [
            ['token', 'string'],
            ['queue_id', 'integer'],
        ];
    }

    public function getResponseData()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if (!\Yii::$app->queue->isDone($this->queue_id)) {
            return [
                'code' => 0,
                'msg' => '',
                'data' => [
                    'retry' => 1
                ]
            ];
        }
        $result = OrderSubmitResult::findOne([
            'token' => $this->token
        ]);
        if ($result) {
            return [
                'code' => 1,
                'msg' => $result->data,
            ];
        }
        return [
            'code' => 0,
            'msg' => '操作成功'
        ];
    }
}
