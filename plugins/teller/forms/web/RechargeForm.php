<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\teller\forms\web;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\Recharge;

class RechargeForm extends Model
{
    public function rules()
    {
        return [];
    }

    //GET
    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        };

        try {
            $list = Recharge::find()->where([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ])
                ->with(['member' => function ($query) {
                    $query->where(['status' => 1, 'is_delete' => 0]);
                }])
                ->all();

            $list = array_map(function($item) {
                $member = [];
                if ($item->member) {
                    $member = [
                        'id' => $item->member->id,
                        'level' => $item->member->level,
                        'name' => $item->member->name,
                    ];
                }
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'pay_price' => $item->pay_price,
                    'send_price' => $item->send_price,
                    'send_integral' => $item->send_integral,
                    'member' => $member
                ];
            }, $list);
            
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'list' => $list
                ],
            ];
        }catch(\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine()
            ];
        }
    }

}
