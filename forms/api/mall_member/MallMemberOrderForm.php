<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\mall_member;


use app\bootstrap\payment\PaymentOrder;
use app\bootstrap\response\ApiCode;
use app\forms\common\CommonMallMember;
use app\models\Mall;
use app\models\MallMemberOrders;
use app\models\MallMembers;
use app\models\Model;
use app\models\Order;
use app\models\User;
use yii\helpers\ArrayHelper;

class MallMemberOrderForm extends Model
{
    public $member_level;

    public function rules()
    {
        return [
            [['member_level'], 'required'],
            [['member_level'], 'integer'],
        ];
    }

    public function purchaseMallMember()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $user = User::find()->where(['id' => \Yii::$app->user->id])->with('identity')->one();
            $currentLevel = $user->identity->member_level;
            if ($currentLevel >= $this->member_level) {
                throw new \Exception('购买的会员不能小于或等于当前会员');
            }
            $mall = new Mall();
            $setting  = $mall->getMallSetting();
            if ($setting['setting']['member_grade']){  // 是否开启会员等级叠加  <@jayi>
                $members = CommonMallMember::getAllMember();
                // 会员升级记录详情
                $detail = [
                    'before_level' => $currentLevel,
                    'after_level' => (int)$this->member_level
                ];
                // 需要累计金额的会员
                $newMembers = [];
                $payPrice = 0;
                foreach ($members as $member) {
                    if ($member['level'] == $currentLevel) {
                        $detail['before_update'] = ArrayHelper::toArray($member);
                    }
                    if ($member['level'] > $currentLevel
                        && $member['level'] <= $this->member_level
                        && $member['is_purchase'] == 1
                    ) {
                        array_push($newMembers, ArrayHelper::toArray($member));
                        $payPrice += $member['price'];
                    }
                }
                if (!$newMembers) {
                    throw new \Exception('该会员不可购买');
                }
            }else{
                $member = MallMembers::find()->where(['mall_id' => \Yii::$app->mall->id, 'level' => $this->member_level])->with(['rights'])->asArray()->one();
                // 会员升级记录详情
                $detail = [
                    'before_level' => $currentLevel,
                    'after_level' => (int)$this->member_level
                ];
                $newMembers = [ArrayHelper::toArray($member)];
                $payPrice = $member['price'];
            }
            $detail['after_update'] = $newMembers;

            $order = new MallMemberOrders();
            $order->user_id = \Yii::$app->user->id;
            $order->mall_id = \Yii::$app->mall->id;
            $order->order_no = Order::getOrderNo('MM');
            $order->pay_price = $payPrice;
            $order->pay_type = MallMemberOrders::PAY_TYPE_ON_LINE;
            $order->detail = \Yii::$app->serializer->encode($detail);
            $res = $order->save();

            if (!$res) {
                throw new \Exception($this->getErrorMsg($order));
            }

            $payOrder = new PaymentOrder([
                'title' => '购买会员',
                'amount' => floatval($payPrice),
                'orderNo' => $order->order_no,
                'notifyClass' => MallMemberPayNotify::class,
                'supportPayTypes' => [ //选填，支持的支付方式，若不填将支持所有支付方式。
                    \app\bootstrap\payment\Payment::PAY_TYPE_WECHAT,
                    \app\bootstrap\payment\Payment::PAY_TYPE_ALIPAY,
                    \app\bootstrap\payment\Payment::PAY_TYPE_BALANCE,
                    \app\bootstrap\payment\Payment::PAY_TYPE_BAIDU,
                    \app\bootstrap\payment\Payment::PAY_TYPE_TOUTIAO,
                    \app\bootstrap\payment\Payment::PAY_TYPE_WECHAT_H5,
                    \app\bootstrap\payment\Payment::PAY_TYPE_ALIPAY_H5
                ],
            ]);
            $id = \Yii::$app->payment->createOrder($payOrder);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '订单创建成功',
                'data' => [
                    'pay_id' => $id
                ]
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
