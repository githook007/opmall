<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/14
 * Time: 14:35
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\forms\api;


use app\bootstrap\response\ApiCode;
use app\models\Mall;
use app\models\Model;
use app\models\OrderSubmitResult;
use app\models\User;
use app\plugins\bargain\forms\common\CommonBargainOrder;
use app\plugins\bargain\models\BargainOrder;

/**
 * @property Mall $mall
 * @property User $user
 */
class BargainResultForm extends ApiModel
{
    public $mall;
    public $user;

    public $queueId;
    public $token;

    public function rules()
    {
        return [
            [['queueId', 'token'], 'required']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if ($this->queueId != 'undefined' && !\Yii::$app->queue->isDone($this->queueId)) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'retry' => 1
                ]
            ];
        }

        $commonBargainOrder = CommonBargainOrder::getCommonBargainOrder($this->mall);
        /* @var OrderSubmitResult $orderSubmitResult */
        $orderSubmitResult = $commonBargainOrder->getBargainOrderResult($this->token);
        if ($orderSubmitResult) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $orderSubmitResult->data
            ];
        }
        /* @var BargainOrder $bargainOrder */
        $bargainOrder = $commonBargainOrder->getTokenOrder($this->token);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'bargain_order_id' => $bargainOrder ? $bargainOrder->id : ''
            ]
        ];
    }
}
