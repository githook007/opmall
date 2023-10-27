<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\mall\wlhulian;

use Alipay\AlipayRequestFactory;
use app\bootstrap\payment\PaymentOrder;
use app\bootstrap\response\ApiCode;
use app\forms\admin\order\AppPayment;
use app\forms\admin\PaySettingForm;
use app\forms\common\CertSN;
use app\models\Model;

class OrderPayForm extends Model
{
    public $pay_price;
    public $pay_type;
    
    public function rules()
    {
        return [
            [['pay_price', 'pay_type'], 'required'],
            [['pay_price'], 'number'],
            [['pay_type'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'pay_price' => '支付金额',
            'pay_type' => '支付方式',
        ];
    }

    //GET
    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try{
            $order_no = 'WL'.date("YmdHis").rand(100, 999);

            $supportPayTypes = [
                PaymentOrder::PAY_TYPE_WECHAT_SCAN,
                PaymentOrder::PAY_TYPE_WECHAT_SCAN,
            ];

            $paymentOrder = new PaymentOrder([
                'title' => '聚合配送余额充值',
                'amount' => (float)$this->pay_price,
                'orderNo' => $order_no,
                'notifyClass' => PayNotify::class,
                'supportPayTypes' => $supportPayTypes,
            ]);

            $payment_order_union_id = \Yii::$app->payment->createOrder([$paymentOrder]);
            $out_trade_no = \Yii::$app->payment->getPaymentOrderUnion($payment_order_union_id)->order_no;

            switch ($this->pay_type) {
                case '微信':
                    $instance = AppPayment::getInstance('wechat');
                    $paymentData= [
                        'nonce_str' => md5(uniqid()),
                        'body' => $paymentOrder->title,
                        'out_trade_no' => $out_trade_no,
                        'total_fee' => $this->pay_price * 100,
                        'trade_type' => 'NATIVE',
                        'notify_url' => $this->getNotifyUrl()
                    ];

                    $res = $instance->getService()->unifiedOrder($paymentData);
                    if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
                        $codeUrl = $instance->getGeneralQrcode(['token' => $res['code_url']]);
                    } else {
                        throw new \Exception($res['return_msg']);
                    }
                    break;

                case '支付宝':
                    $instance = AppPayment::getInstance('alipay');
                    $aop = $instance->getService();

                    $bizContent = [
                        'out_trade_no' => $out_trade_no,
                        'total_amount' => $this->pay_price,
                        'subject' => $paymentOrder->title,
                    ];

                    $setting = (new PaySettingForm())->getOption();

                    $request = AlipayRequestFactory::create('alipay.trade.precreate', [
                        'notify_url' => $this->getNotifyUrl('alipay-native'),
                        'biz_content' => $bizContent,
                        'app_cert_sn' => CertSN::getSn($setting['alipay_appcert']),
                        'alipay_root_cert_sn' => CertSN::getSn($setting['alipay_rootcert'], true)
                    ]);

                    $res = $aop->execute($request)->getData();
                    if ($res['code'] == '10000') {
                        $codeUrl = $instance->getGeneralQrcode(['token' => $res['qr_code']]);
                    } else {
                        throw new \Exception($res['msg']);
                    }
                    break;

                default:
                    throw new \Exception('未知支付方式');
                    break;
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '下单成功',
                'data' => [
                    'code_url' => $codeUrl,
                    'pay_type' => $this->pay_type,
                    'order_no' => $order_no,
                ]
            ];
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }

    }

    public function getNotifyUrl($type = 'wechat-native')
    {
        $protocol = env('PAY_NOTIFY_PROTOCOL');
        $url = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/pay-notify/{$type}-wl.php";
        if ($protocol) {
            $url = str_replace('http://', ($protocol . '://'), $url);
            $url = str_replace('https://', ($protocol . '://'), $url);
        }
        return $url;
    }
}
