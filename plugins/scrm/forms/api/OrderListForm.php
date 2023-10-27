<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/23
 * Time: 9:37 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\common\platform\PlatformConfig;
use app\forms\mall\order\BaseOrderForm;
use app\models\Express;
use app\models\Order;
use app\models\OrderDetailExpress;
use app\models\User;
use app\plugins\scrm\exception\ScrmException;

class OrderListForm extends BaseOrderForm
{
    public function search()
    {
        try {
            if (!$this->validate()) {
                return $this->getErrorResponse();
            }
            $this->app_clerk = true;
            $user = User::findOne([
                'id' => $this->user_id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id
            ]);
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }
            $query = $this->getAllQuery();
            try {
                \Yii::$app->plugin->getPlugin('mch');
                $query->with('mch.store', 'detail.goods.mch.store');
            } catch (\Exception $exception) {

            }

            $list = $query->apiPage()
                ->orderBy($this->order_by . 'o.created_at DESC')
                ->select(['o.*', 'u.nickname'])
                ->with(['detail.refund', 'detail.goods.goodsWarehouse'])
                ->with('detail.expressRelation')
                ->with('clerk', 'detailExpressRelation')
                ->with('user.userInfo')
                ->with('store', 'orderClerk')
                ->with('detailExpress.expressRelation.orderDetail.expressRelation')
                ->with('detailExpress.expressSingle')
                ->asArray()
                ->all();

            /** @var User[] $userList */
            $userList = User::find()->with(['userInfo', 'userPlatform'])
                ->where(['id' => array_column($list, 'user_id')])
                ->all();
            $userPlatformList = [];
            foreach ($userList as $user) {
                $userPlatformList[$user->id] = [
                    'platform_icon' => PlatformConfig::getInstance()->getPlatformIcon($user)
                ];
            }

            $order = new Order();
            foreach ($list as &$item) {
                $item['platform_icon'] = $userPlatformList[$item['user_id']]['platform_icon'];
                //插件名称
                if ($item['sign'] == '' && $item['mch_id'] == 0) {
                    $item['plugin_name'] = '商城';
                } elseif ($item['mch_id'] > 0) {
                    $item['plugin_name'] = isset($item['mch']['store']['name']) ? $item['mch']['store']['name'] : '多商户';
                } else {
                    try {
                        $item['plugin_name'] = \Yii::$app->plugin->getPlugin($item['sign'])->getDisplayName();
                    } catch (\Exception $exception) {
                        $item['plugin_name'] = '未知插件';
                    }
                }
                // 商家留言
                $merchantRemarkList = [];
                /** @var OrderDetailExpress $detailExpress */
                foreach ($item['detailExpress'] as &$detailExpress) {
                    if ($detailExpress['send_type'] == 1 && $detailExpress['merchant_remark']) {
                        $merchantRemarkList[] = $detailExpress['merchant_remark'];
                    }
                    foreach ($detailExpress['expressRelation'] as &$expressRelation) {
                        $expressRelation['orderDetail']['goods_info'] = \Yii::$app->serializer->decode($expressRelation['orderDetail']['goods_info']);
                    }
                    unset($expressRelation);
                }
                $item['merchant_remark_list'] = $merchantRemarkList;
                unset($detailExpress);

                $item['order_form'] = json_decode($item['order_form'], true);
                $item['is_show_send_type'] = 1;
                $item['goods_type'] = 'goods';
                $item['is_show_express'] = 0;
                $item['goods_num'] = 0;
                $priceList = [];
                foreach ($item['detail'] as $key => &$detail) {
                    $item['goods_num'] += $detail['num'];
                    $goods_info = \Yii::$app->serializer->decode($detail['goods_info']);
                    $item['detail'][$key]['attr_list'] = $goods_info['attr_list'];
                    $goods_info['is_show_express'] = 1;
                    $detail['goods_type'] = 'goods';
                    if (isset($goods_info['goods_attr']['goods_type'])) {
                        $item['is_show_send_type'] = $goods_info['goods_attr']['goods_type'] == 'ecard' ? 0 : 1;
                        $goods_info['is_show_express'] = $goods_info['goods_attr']['goods_type'] == 'ecard' ? 0 : 1;
                        $item['goods_type'] = $goods_info['goods_attr']['goods_type'];
                        $detail['goods_type'] = $goods_info['goods_attr']['goods_type'];
                    }
                    $item['is_show_express'] = $item['is_show_express'] || $goods_info['is_show_express'] ? 1 : 0;

                    $refund_status = 0;
                    if ($detail['refund']) {
                        $refund_status = $detail['refund']['status'];
                    }
                    $item['detail'][$key]['refund_status'] = $refund_status;
                    $detail['goods_info'] = \Yii::$app->serializer->decode($detail['goods_info']);

                    //插件名称
                    // 使用订单sign的插件
                    $orderSign = ['gift', 'composition', 'exchange'];
                    if (in_array($item['sign'], $orderSign)) {
                        $detail['plugin_name'] = $item['plugin_name'];
                    } elseif ($detail['goods']['sign'] == '') {
                        $detail['plugin_name'] = '商城';
                    } elseif ($detail['goods']['mch_id'] > 0) {
                        $detail['plugin_name'] = isset($detail['goods']['mch']['store']['name']) ? $detail['goods']['mch']['store']['name'] : '多商户';
                    } else {
                        try {
                            $detail['plugin_name'] = \Yii::$app->plugin->getPlugin($detail['goods']['sign'])->getDisplayName();
                        } catch (\Exception $exception) {
                            $detail['plugin_name'] = '未知插件';
                        }
                    }

                    $detail['refund_status_text'] = $this->getRefundStatusText($detail);
                    $priceList[] = [
                        'label' => '小计',
                        'value' => $detail['total_price'],
                    ];
                    unset($detail['goods']['goodsWarehouse']['detail']);
                    unset($detail['goods_info']['goods_attr']['detail']);
                }
                unset($detail);

                // 订单剩余未发货的商品数量,等于0则代表已全部发货
                $item['not_send_count'] = count($item['detail']) - count($item['detailExpressRelation']);
                // TODO 兼容旧的订单 2019-10-24
                if ($item['is_send'] == 1 && count($item['detailExpressRelation']) == 0) {
                    $item['not_send_count'] = 0;
                }

                // 控制订单操作 是否显示(例如拼团)
                $item['is_send_show'] = $this->is_send_show;
                $item['is_cancel_show'] = $this->is_cancel_show;
                $item['is_clerk_show'] = $this->is_clerk_show;
                $item['is_confirm_show'] = $this->is_confirm_show;
                $item['is_update_send'] = 1;

                //社区团购不显示发货按钮，自动发货
                if ($item['sign'] == 'community') {
                    $item['is_send_show'] = 0;
                    $item['is_update_send'] = 0;
                }

                if ($item['sign'] == 'weekly_buy') {
                    // 周期购订单，第一期发货后不能修改地址和取消订单
                    if (!empty($item['detailExpressRelation'])) {
                        $item['is_cancel_show'] = 0;
                        $item['is_edit_address'] = 0;
                    }
                }

                $item['plugin_data'] = $order->getPluginData($item, $priceList);
                // 订单操作状态
                $item['action_status'] = $order->getOrderActionStatus($item);
                // 电子面单列表
                $item['new_express_single'] = $order->getExpressSingleList($item);
                $item['cancel_data'] = $item['cancel_data'] ? json_decode($item['cancel_data'], true) : [];
                $item['send_template_discount_price'] = price_format($item['total_goods_original_price'] + $item['express_price'] - $item['total_pay_price']);
                unset($item['user']);
            }

            return $this->success([
                'list' => $list,
            ]);
        } catch (\Exception $exception) {
            throw $exception;
            return $this->failByException($exception);
        }
    }

    private function getRefundStatusText($orderDetail)
    {
        $text = '';
        if ($orderDetail['refund']) {
            $text = '售后中';
            $refund = $orderDetail['refund'];

            if ($refund['status'] == 3) {
                $text = '已拒绝';
            }

            if ($refund['type'] == 2 && $refund['is_confirm'] == 1) {
                $text = '已换货';
            }

            if (($refund['type'] == 1 || $refund['type'] == 3) && $refund['is_refund'] == 1) {
                $text = '已退款';
            }
        }

        return $text;
    }
}
