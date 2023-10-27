<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/24
 * Time: 3:48 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\common\CommonDelivery;
use app\forms\common\platform\PlatformConfig;
use app\helpers\Json;
use app\models\Order;
use app\models\User;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;

class OrderDetailForm extends Model
{
    public $user_id;
    public $order_id;

    public function rules()
    {
        return [
            [['user_id', 'order_id'], 'required'],
            [['user_id', 'order_id'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            /** @var User $user */
            $user = User::find()->where([
                'id' => $this->user_id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id
            ])->with('userInfo', 'userPlatform')->one();
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }

            /** @var Order $order */
            $order = Order::find()->where([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->order_id,
                'is_delete' => 0,
                'user_id' => $this->user_id
            ])
                ->with('shareOrder.user', 'refund', 'clerk', 'orderClerk', 'store')
                ->with('refund')
                ->with('paymentOrder.paymentOrderUnion')
                ->with('detailExpress')
                ->one();

            if (!$order) {
                throw new ScrmException('订单不存在');
            }
            $res = $order->toArray();
            $res['refund_info'] = [];
            if ($order->refund) {
                $firstRefund = $order->refund[0];
                $firstRefund->refund_data = Json::decode($firstRefund->refund_data, true, []);
                $res['refund_info'] = $firstRefund;
                $res['refund'] = $firstRefund->statusText_business($firstRefund);
            }
            $res['goods_type'] = 'goods';
            $order['platform'] = PlatformConfig::getInstance()->getPlatform($user);
            $existsFormIds = [];
            $res['goods_num'] = 0;
            foreach ($order->detail as $item) {
                $detailTemp = $item->toArray();
                $res['goods_num'] += $item->num;
                $goodsAttr = Json::decode($item->goods_info, true);
                if (isset($goodsAttr['goods_attr']['goods_type'])) {
                    $res['goods_type'] = $goodsAttr['goods_attr']['goods_type'];
                }
                //兼容
                if(is_string($goodsAttr['goods_attr']['pic_list'])){
                    $goodsAttr['goods_attr']['pic_list'] = Json::decode($goodsAttr['goods_attr']['pic_list'], true);
                }
                $detailTemp['attr_list'] = $goodsAttr['attr_list'];
                $detailTemp['goods_temp'] = $goodsAttr['goods_attr'];
                if ($item->form_id) {
                    if (in_array($item->form_id, $existsFormIds)) {
                        $detailTemp['same_form'] = true;
                    } else {
                        $detailTemp['form_data'] = Json::decode($item->form_data, true, []);
                    }
                }
                unset($detailTemp['goods_info']);
                $res['detail'][] = $detailTemp;
            }
            $res['order_form'] = Json::decode($order->order_form, true, []);
            $res['city'] = Json::decode($order['city_info'], true, []);
            if ($order->send_type == 2) {
                $commonDelivery = CommonDelivery::getInstance();
                $cityConfig = $commonDelivery->getConfig();
                $res['city']['address'] = $cityConfig['address']['address'] ?? '';
                $res['city']['explain'] = $cityConfig['explain'] ?? '';
                $res['city']['contact_way'] = $cityConfig['contact_way'] ?? '';
            }
            $res['plugin_data'] = $order->getPluginData($order, []);
            $res['cancel_data'] = Json::decode($order->cancel_data, true, []);
            $res['send_template_discount_price'] = price_format($order->total_goods_original_price - $order->total_goods_price);
            $res['detailExpress'] = array_map(function ($item) {
                return $item->toArray([
                    'express',
                    'send_type',
                    'express_no',
                    'merchant_remark',
                    'express_content',
                    'city_name',
                    'city_mobile'
                ]);
            }, $order->detailExpress);
            return $this->success([
                'order' => $res
            ]);
        } catch (\Exception $exception) {
            throw $exception;
            return $this->failByException($exception);
        }
    }
}
