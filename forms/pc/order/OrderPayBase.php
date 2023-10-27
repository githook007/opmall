<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/16 10:46
 */

namespace app\forms\pc\order;

use app\bootstrap\payment\PaymentOrder;
use app\forms\mall\pay_type_setting\CommonPayType;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderVipCardInfo;
use app\models\PayType;
use app\plugins\wxapp\forms\WechatServicePay;
use app\plugins\wxapp\models\WxappConfig;
use app\plugins\wxapp\models\WxappWxminiprograms;
use luweiss\Wechat\WechatPay;

class OrderPayBase extends Model
{
    /**
     * @param Order[] $orders
     * @return array
     * @throws \app\bootstrap\payment\PaymentException
     * @throws \yii\db\Exception
     */
    protected function getReturnData($orders)
    {
        $hasVipCardOrder = false; //有超级会员卡
        foreach ($orders as $order) {
            if ($order->sign === 'vip_card') {
                $hasVipCardOrder = true;
                break;
            }
        }

        $payment = \Yii::$app->payment;

        $supportPayTypes = [
            $payment::PAY_TYPE_WECHAT, // 目前只有微信扫码付款
        ];
        $paymentOrders = [];
        $orderData = ["total_pay_price" => 0, "order_goods_name" => []];
        foreach ($orders as $order) {
            $totalPayPrice = $order->total_pay_price;
            if ($hasVipCardOrder) {
                $orderVipCardInfo = OrderVipCardInfo::findOne([
                    'order_id' => $order->id,
                ]);
                if ($orderVipCardInfo) {
                    $totalPayPrice = $orderVipCardInfo->order_total_price;
                }
            }
            $paymentOrder = new PaymentOrder([
                'title' => $this->getOrderTitle($order),
                'amount' => (float)$totalPayPrice,
                'orderNo' => $order->order_no,
                'notifyClass' => OrderPayNotify::class,
                'supportPayTypes' => $supportPayTypes,
            ]);
            $paymentOrders[] = $paymentOrder;

            $orderData['total_pay_price'] += (float)$totalPayPrice;
            foreach ($order->detail as $key => $orderDetail) {
                try {
                    $goodsAttrInfo = \Yii::$app->serializer->decode($orderDetail->goods_info);
                    if(isset($goodsAttrInfo['goods_attr']['name'])) {
                        $orderData["order_goods_name"][] = $goodsAttrInfo['goods_attr']['name'];
                    }
                } catch (\Exception $exception) {}
            }
        }
        $id = $payment->createOrder($paymentOrders);
        $newPayType = (CommonPayType::get('pc_manage'))['wx'];
        if ($newPayType) {
            $config = [];
            $payType = PayType::findOne($newPayType);
            if ($payType->is_service) {
                if ($payType->service_cert_pem && $payType->service_key_pem) {
                    $this->generatePem($config, $payType->service_cert_pem, $payType->service_key_pem);
                }
                $wechatPay = new WechatServicePay(array_merge([
                    'appId' => $payType->service_appid,
                    'mchId' => $payType->service_mchid,
                    'key' => $payType->service_key,
                    'sub_appid' => $payType->appid,
                    'sub_mch_id' => $payType->mchid
                ], $config));
            } else {
                if ($payType->cert_pem && $payType->key_pem) {
                    $this->generatePem($config, $payType->cert_pem, $payType->key_pem);
                }
                $wechatPay = new WechatPay(array_merge([
                    'appId' => $payType->appid,
                    'mchId' => $payType->mchid,
                    'key' => $payType->key,
                ], $config));
            }
        } else {
            $third = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
            /**@var WxappConfig $wxappConfig**/
            $wxappConfig = WxappConfig::find()
                ->with(['service'])
                ->where([
                    'mall_id' => \Yii::$app->mall->id,
                ])
                ->one();
            if (!$wxappConfig) {
                throw new \Exception('微信小程序支付尚未配置。');
            }
            $config = [];
            if ($wxappConfig->service && $wxappConfig->service->is_choise == 1) {
                if ($wxappConfig->service->cert_pem && $wxappConfig->service->key_pem) {
                    $this->generatePem($config, $wxappConfig->service->cert_pem, $wxappConfig->service->key_pem);
                }
                $wechatPay = new WechatServicePay(array_merge([
                    'appId' => $wxappConfig->service->appid,
                    'mchId' => $wxappConfig->service->mchid,
                    'key' => $wxappConfig->service->key,
                    'sub_appid' => $third ? $third->authorizer_appid : $wxappConfig->appid,
                    'sub_mch_id' => $wxappConfig->mchid
                ], $config));
            } else {
                if ($wxappConfig->cert_pem && $wxappConfig->key_pem) {
                    $this->generatePem($config, $wxappConfig->cert_pem, $wxappConfig->key_pem);
                }
                $wechatPay = new WechatPay(array_merge([
                    'appId' => $third ? $third->authorizer_appid : $wxappConfig->appid,
                    'mchId' => $wxappConfig->mchid,
                    'key' => $wxappConfig->key,
                ], $config));
            }
        }
        $paymentOrderUnion = $payment->getCheckPayData($id, $payment::PAY_TYPE_WECHAT);
        $res = $wechatPay->unifiedOrder([
            'body' => $paymentOrderUnion->title,
            'out_trade_no' => $paymentOrderUnion->order_no,
            'total_fee' => $paymentOrderUnion->amount * 100,
            'notify_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/pay-notify/wechat.php',
            'trade_type' => WechatPay::TRADE_TYPE_NATIVE,
        ]);
        if(empty($res['code_url'])) {
            throw new \Exception("获取微信支付扫码失败");
        }
        $orderData['order_goods_name'] = implode("；", $orderData['order_goods_name']);
        $qrCodeUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . '?r=pc/web/index/qr-code&mall_id='.base64_encode(\Yii::$app->mall->id).'&url='.urlencode($res['code_url']);
        return [
            'code' => 0,
            'data' => ['url' => $qrCodeUrl, "listen" => $id, "order" => $orderData],
        ];
    }

    protected function getOrderTitle(Order $order)
    {
        /** @var OrderDetail[] $details */
        $details = $order->getDetail()->andWhere(['is_delete' => 0])->with('goods')->all();
        if (!$details || !is_array($details) || !count($details)) {
            return $order->order_no;
        }
        $titles = [];
        foreach ($details as $detail) {
            if (!$detail->goods) {
                continue;
            }
            $titles[] = $detail->goods->name;
        }
        $title = implode(';', $titles);
        if (mb_strlen($title) > 32) {
            return mb_substr($title, 0, 32);
        } else {
            return $title;
        }
    }
    private function generatePem(&$config, $cert_pem, $key_pem)
    {
        $pemDir = \Yii::$app->runtimePath . '/pem';
        make_dir($pemDir);
        $certPemFile = $pemDir . '/' . md5($cert_pem);
        if (!file_exists($certPemFile)) {
            file_put_contents($certPemFile, $cert_pem);
        }
        $keyPemFile = $pemDir . '/' . md5($key_pem);
        if (!file_exists($keyPemFile)) {
            file_put_contents($keyPemFile, $key_pem);
        }
        $config['certPemFile'] = $certPemFile;
        $config['keyPemFile'] = $keyPemFile;
    }

}
