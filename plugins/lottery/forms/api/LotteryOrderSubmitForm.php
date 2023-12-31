<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\lottery\forms\api;

use app\forms\api\order\OrderException;
use app\forms\api\order\OrderSubmitForm;
use app\plugins\lottery\forms\common\CommonLottery;
use app\plugins\lottery\models\LotteryLog;

class LotteryOrderSubmitForm extends OrderSubmitForm
{
    public $form_data;
    public $lotteryLog;

    public function rules()
    {
        return [
            [['form_data'], 'required'],
        ];
    }

    public function subGoodsNum($goodsAttr, $subNum, $goodsItem)
    {
    }

    public function checkGoodsStock($goodsList)
    {
        return true;
    }

    public function checkGoods($goods, $item)
    {
        $log_id = $this->form_data->list[0]['lottery_log_id'];

        $lotteryLog = LotteryLog::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'user_id' => \Yii::$app->user->id,
            'status' => 3,
            'id' => $log_id
        ])->one();
        if (!$lotteryLog) {
            throw new OrderException('奖品已兑换或不存在');
        }
        $this->lotteryLog = $lotteryLog;
    }

    public function getGoodsItemData($item)
    {

        $itemData = parent::getGoodsItemData($item);
        $itemData['num'] = 1;
        $itemData['forehead_integral'] = 0;
        $itemData['forehead_integral_type'] = 0;
        $itemData['accumulative'] = 0;

        $itemData['total_original_price'] = 0;
        $itemData['total_price'] = 0;

        $itemData['discounts'] = [];
        $itemData['is_level_alone'] = 0;
        return $itemData;
    }

    public function getSendType($mchItem)
    {
        $setting = CommonLottery::getSetting();
        if ($setting) {
            $sendType = $setting['send_type'];
        } else {
            $sendType = ['express', 'offline'];
        }
        return $sendType;
    }

    public function getToken()
    {
        return $this->lotteryLog->token ?: parent::getToken();
    }

    public function whiteList()
    {
        return [$this->sign];
    }

    protected function checkGoodsBuyLimit($goodsList)
    {
        return true;
    }
}
