<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/16 16:11
 */


namespace app\controllers\system;

use app\controllers\Controller;
use app\bootstrap\payment\PaymentNotify;
use app\forms\admin\order\AppPayment;
use app\forms\wlhulian\CommonForm;
use app\models\Mall;
use app\models\PaymentOrder;
use app\models\PaymentOrderUnion;
use luweiss\Wechat\WechatHelper;
use yii\web\Response;

class WlhulianNotifyController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    private function checkWechatsign($res)
    {
        $instance = AppPayment::getInstance('wechat');
        $truthSign = $instance->getService()->makeSign($res);
        if ($truthSign !== $res['sign']) {
            throw new \Exception('签名验证失败。');
        }
    }

    private function checkAlipaySign()
    {
        try {
            $instance = AppPayment::getInstance('alipay');
            $passed = $instance->getService()->verify();
        } catch (\Exception $ex) {
            $passed = null;
            printf('%s | %s' . PHP_EOL, get_class($ex), $ex->getMessage()); // 验证过程发生错误，打印异常信息
            \Yii::error($ex->getMessage());
        }

        return $passed;
    }

    public function actionWechatNative()
    {
        \Yii::$app->response->format = Response::FORMAT_XML;
        $xml = \Yii::$app->request->rawBody;
        $res = WechatHelper::xmlToArray($xml);
        if (!$res) {
            throw new \Exception('请求数据错误: ' . $xml);
        }
        if (empty($res['out_trade_no'])
            || empty($res['sign'])
            || empty($res['total_fee'])
            || empty($res['result_code'])
            || empty($res['return_code'])
        ) {
            throw new \Exception('请求数据错误: ' . $xml);
        }

        if ($res['result_code'] !== 'SUCCESS' || $res['return_code'] !== 'SUCCESS') {
            throw new \Exception('订单尚未支付: ' . $xml);
        }

        $paymentOrderUnion = PaymentOrderUnion::findOne([
            'order_no' => $res['out_trade_no'],
        ]);
        if (!$paymentOrderUnion) {
            throw new \Exception('订单不存在: ' . $res['out_trade_no']);
        }
        if ($paymentOrderUnion->app_version) {
            \Yii::$app->setAppVersion($paymentOrderUnion->app_version);
        }
        if ($paymentOrderUnion->is_pay === 1) {
            $responseData = [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK',
            ];
            \Yii::$app->response->format = Response::FORMAT_XML;
            echo WechatHelper::arrayToXml($responseData);
            return;
        }
        $mall = Mall::findOne($paymentOrderUnion->mall_id);
        if (!$mall) {
            throw new \Exception('未查询到id=' . $paymentOrderUnion->id . '的商城。 ');
        }
        \Yii::$app->setMall($mall);

        $this->checkWechatSign($res);

        $paymentOrderUnionAmount = (doubleval($paymentOrderUnion->amount) * 100) . '';
        if (intval($res['total_fee']) !== intval($paymentOrderUnionAmount)) {
            throw new \Exception('支付金额与订单金额不一致。');
        }

        $paymentOrders = PaymentOrder::findAll(['payment_order_union_id' => $paymentOrderUnion->id]);
        $paymentOrderUnion->is_pay = 1;
        $paymentOrderUnion->pay_type = 7;
        if (!$paymentOrderUnion->save()) {
            throw new \Exception($paymentOrderUnion->getFirstErrors());
        }
        foreach ($paymentOrders as $paymentOrder) {
            $Class = $paymentOrder->notify_class;
            if (!class_exists($Class)) {
                continue;
            }
            $paymentOrder->is_pay = 1;
            $paymentOrder->pay_type = 1;
            if (!$paymentOrder->save()) {
                throw new \Exception($paymentOrder->getFirstErrors());
            }
            /** @var PaymentNotify $notify */
            $notify = new $Class();
            try {
                $po = new \app\bootstrap\payment\PaymentOrder([
                    'orderNo' => $paymentOrder->order_no,
                    'amount' => (float)$paymentOrder->amount,
                    'title' => $paymentOrder->title,
                    'notifyClass' => $paymentOrder->notify_class,
                ]);
                $notify->notify($po);
            } catch (\Exception $e) {
                \Yii::error("wl微信支付通知结果异常");
                \Yii::error($e);
            }
        }
        $responseData = [
            'return_code' => 'SUCCESS',
            'return_msg' => 'OK',
        ];
        \Yii::$app->response->format = Response::FORMAT_XML;
        echo WechatHelper::arrayToXml($responseData);
    }

    public function actionAlipayNative()
    {
        $res = \Yii::$app->request->post();
        if (!$res) {
            throw new \Exception('请求数据错误');
        }
        if (empty($res['out_trade_no'])
            || empty($res['sign'])
            || empty($res['total_amount'])
        ) {
            throw new \Exception('请求数据错误');
        }

        $paymentOrderUnion = PaymentOrderUnion::findOne([
            'order_no' => $res['out_trade_no'],
        ]);
        if (!$paymentOrderUnion) {
            throw new \Exception('订单不存在: ' . $res['out_trade_no']);
        }
        if ($paymentOrderUnion->app_version) {
            \Yii::$app->setAppVersion($paymentOrderUnion->app_version);
        }
        if ($paymentOrderUnion->is_pay === 1) {
            return;
        }
        $mall = Mall::findOne($paymentOrderUnion->mall_id);
        if (!$mall) {
            throw new \Exception('未查询到id=' . $paymentOrderUnion->id . '的商城。 ');
        }
        \Yii::$app->setMall($mall);

        $passed = $this->checkAlipaySign();

        if ($passed) {
            $paymentOrders = PaymentOrder::findAll(['payment_order_union_id' => $paymentOrderUnion->id]);
            $paymentOrderUnion->is_pay = 1;
            $paymentOrderUnion->pay_type = 4;
            if (!$paymentOrderUnion->save()) {
                throw new \Exception($paymentOrderUnion->getFirstErrors());
            }
            foreach ($paymentOrders as $paymentOrder) {
                $Class = $paymentOrder->notify_class;
                if (!class_exists($Class)) {
                    continue;
                }
                $paymentOrder->is_pay = 1;
                $paymentOrder->pay_type = 4;
                if (!$paymentOrder->save()) {
                    throw new \Exception($paymentOrder->getFirstErrors());
                }
                /** @var PaymentNotify $notify */
                $notify = new $Class();
                try {
                    $po = new \app\bootstrap\payment\PaymentOrder([
                        'orderNo' => $paymentOrder->order_no,
                        'amount' => (float)$paymentOrder->amount,
                        'title' => $paymentOrder->title,
                        'notifyClass' => $paymentOrder->notify_class,
                    ]);
                    $notify->notify($po);
                } catch (\Exception $e) {
                    \Yii::error($e);
                }
            }
            echo "success";
        }
    }

    public function actionMsg()
    {
        try {
            \Yii::warning('聚合配送回调');
            $data = json_decode(\Yii::$app->request->rawBody, true);
            \Yii::warning($data);

            CommonForm::msgNotify($data);

            return 'success';
        } catch (\Exception $exception) {
            \Yii::error('聚合配送回调出错');
            \Yii::error($exception);
            exit;
        }
    }
}
