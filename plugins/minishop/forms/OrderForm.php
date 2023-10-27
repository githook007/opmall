<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/20
 * Time: 3:47 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\forms\common\platform\PlatformConfig;
use app\models\Order;
use app\plugins\minishop\models\MinishopOrder;
use app\plugins\wxapp\models\shop\ShopFactory;
use yii\helpers\Json;

class OrderForm extends Model
{
    /**
     * @var Order $order
     */
    public $order;
    public $type;
    public $action_type;

    public function execute()
    {
        try {
            if ($this->order->is_pay != 1 || !$this->order->paymentOrder) {
                \Yii::warning('订单未支付时，不需要提交到自定版交易组件上');
                return true;
            }
            $minihopOrder = MinishopOrder::findOne([
                'payment_order_union_id' => $this->order->paymentOrder->payment_order_union_id,
                'mall_id' => \Yii::$app->mall->id,
                'platform_order_id' => $this->order->id,
            ]);
            if (!$minihopOrder) {
                \Yii::warning('未在交易组件中创建订单，不进行后续订单操作' . $this->order->paymentOrder->payment_order_union_id);
                return true;
            }
            $platformList = PlatformConfig::getInstance()->getPlatformOpenid($this->order->user);

            if (!isset($platformList['wxapp'])) {
                \Yii::warning('获取不到微信小程序用户openid不上传到小商店');
                return true;
            }
            $openid = $platformList['wxapp'];
            $form = new CheckForm();
            $plugin = $form->check();
            $shopService = $plugin->getShopService();
            switch ($this->type) {
                case 'pay':
                    if ($minihopOrder->status != 10) {
                        \Yii::warning('订单状态已修改');
                        return true;
                    }
                    $shopService->order->pay([
                        'out_order_id' => $minihopOrder->payment_order_union_no,
                        'openid' => $openid,
                        'action_type' => 1,
                        'action_remark' => '',
                        'transaction_id' => $minihopOrder->payment_order_union_no,
                        'pay_time' => $this->order->pay_time
                    ]);
                    $minihopOrder->status = 20;
                    break;
                case 'cancel':
                    if ($minihopOrder->status != 10) {
                        \Yii::warning('订单状态已修改');
                        return true;
                    }
                    $shopService->order->pay([
                        'out_order_id' => $minihopOrder->payment_order_union_no,
                        'openid' => $openid,
                        'action_type' => $this->action_type,
                        'action_remark' => '',
                    ]);
                    $minihopOrder->status = 250;
                    break;
                case 'send': // @czs 修改发货新增参数
                    $deliveryList = [];
                    if ($this->order->send_type == 0) {
                        $data = Json::decode($minihopOrder->data);
                        $list = $this->getCompanyList($shopService);
                        foreach ($this->order->detail as $detail) {
                            if(!$detail->expressRelation || !$detail->expressRelation->orderExpress){
                                continue;
                            }
                            $id = $detail->expressRelation->orderExpress->id;
                            if(!isset($deliveryList[$id])) {
                                $deliveryList[$id] = [
                                    'delivery_id' => 'OTHERS',
                                    'waybill_id' => 'OTHERS',
                                    'product_info_list' => []
                                ];
                            }
                            if ($detail->expressRelation->orderExpress->send_type == 1) {
                                $deliveryList[$id]['delivery_id'] = $this->tranExpress($list, $detail->expressRelation->orderExpress->express);
                                $deliveryList[$id]['waybill_id'] = $detail->expressRelation->orderExpress->express_no;
                                foreach ($data['order_detail']['product_infos'] as $value) {
                                    if ($value['out_product_id'] == $detail->goods_id) {
                                        $deliveryList[$id]['product_info_list'][] = [
                                            'out_product_id' => $value['out_product_id'],
                                            'out_sku_id' => $value['out_sku_id'],
                                            'product_cnt' => $value['product_cnt']
                                        ];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    $shopService->delivery->send([
                        'out_order_id' => $minihopOrder->payment_order_union_no,
                        'openid' => $openid,
                        'finish_all_delivery' => $this->order->is_send == 1 ? 1 : 0,
                        'delivery_list' => array_values($deliveryList),
                        'ship_done_time' => $this->order->send_time, // @czs
                    ]);
                    if ($this->order->is_send == 1) {
                        $minihopOrder->status = 30;
                    }
                    break;
                case 'confirm':
                    if($minihopOrder->status == 30) {
                        $shopService->delivery->receive([
                            'out_order_id' => $minihopOrder->payment_order_union_no,
                            'openid' => $openid,
                        ]);
                        $minihopOrder->status = 100;
                    }
                    break;
                default:
            }
            if (!$minihopOrder->save()) {
                throw new \Exception($this->getErrorMsg($minihopOrder));
            }
        } catch (\Exception $exception) {
            \Yii::warning($exception);
        }
        return true;
    }

    /**
     * @param ShopFactory $shopService
     */
    public function getCompanyList($shopService)
    {
        if ($list = \Yii::$app->cache->get('minishop_delivery_list')) {
            return $list;
        }
        $res = $shopService->delivery->getCompanyList();
        $list = array_column($res['company_list'], 'delivery_id', 'delivery_name');
        \Yii::$app->cache->set('minishop_delivery_list', $list, 86400);
        return $list;
    }

    public function tranExpress($list, $express)
    {
        if(isset($list[$express])) {
            return $list[$express];
        }else{
            return '';
        }
    }
}
