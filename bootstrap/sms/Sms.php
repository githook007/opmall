<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\bootstrap\sms;

use app\forms\common\CommonAppConfig;
use app\models\CoreValidateCode;
use app\models\CoreValidateCodeLog;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class Sms
{
    protected $config;
    protected $smsConfig;
    protected $easySms;
    protected $ws;

    protected $minutes = 1;// 短信发送间隔（分钟）
    protected static $validityMinutes = 30;// 验证码有效时间（分钟）

    public function __construct($params = [])
    {

        $mchId = isset($params['mch_id']) ? $params['mch_id'] : 0;
        if(isset($params['sms_config'])){
            $this->smsConfig = $params['sms_config'];
        }else {
            $this->smsConfig = CommonAppConfig::getSmsConfig($mchId);
        }

        $this->config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun', 'qcloud'
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/runtime/easy-sms.log',
                ],
                //...
            ],
        ];

        // 阿里云短信配置
        if ($this->smsConfig['platform'] == 'aliyun') {
            $this->config['gateways']['aliyun'] = [
                'access_key_id' => $this->smsConfig['access_key_id'],
                'access_key_secret' => $this->smsConfig['access_key_secret'],
                'sign_name' => $this->smsConfig['template_name'],
            ];
            $this->config['default']['gateways'] = ['aliyun'];
        }else if($this->smsConfig['platform'] == 'txyun'){  // 腾讯云短信配置
            $this->config['gateways']['qcloud'] = [
                'sdk_app_id' => $this->smsConfig['tx_app_id'],
                'app_key' => $this->smsConfig['tx_app_key'],
                'sign_name' => $this->smsConfig['template_name'],
            ];
            $this->config['default']['gateways'] = ['qcloud'];
        }

        $this->easySms = new EasySms($this->config);
    }

    /**
     * 发送短信验证码
     * @param string $mobile
     * @return bool
     * @throws NoGatewayAvailableException
     * @throws \Exception
     */
    public function sendCaptcha(string $mobile)
    {
        $validateDate = date('Y-m-d H:i:s', time() - $this->minutes * 60);
        $coreValidateCode = CoreValidateCode::find()->where([
            'target' => $mobile,
            'is_validated' => CoreValidateCode::IS_VALIDATED_FALSE
        ])->andWhere(['>', 'created_at', $validateDate])->one();

        if ($coreValidateCode) {
            throw new \Exception('操作频繁,请一分钟后再重试');
        }

        try {
            $captcha = (string)mt_rand(100000, 999999);
            $message = new CaptchaMessage($captcha, $this->smsConfig['captcha']);
            $results = $this->easySms->send($mobile, $message);
            $results = array_column($results, "result");
            if(!empty($results[0]['ActionStatus']) && $results[0]['ActionStatus'] == 'FAIL'){
                throw new \Exception($results[0]['ErrorInfo'] ?? '');
            }

            $coreValidateCode = new CoreValidateCode();
            $coreValidateCode->target = $mobile;
            $coreValidateCode->code = $captcha;
            $res = $coreValidateCode->save();

            $this->saveValidateCodeLog($mobile, $message->getContent() . $captcha);
            return true;
        } catch (NoGatewayAvailableException $e) {
            \Yii::error('短信发送失败:' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 发送新订单短信通知
     * @param array $mobile [157.., 183..]
     * @param $orderNo
     * @return bool
     * @throws NoGatewayAvailableException
     * @throws \Exception
     */
    public function sendOrderMessage(array $mobile, $orderNo)
    {
        if (count($mobile) != count($mobile, 1)) {
            throw new \Exception('手机号数组格式错误,请传入一维数组');
        }

        try {
            $message = new NewOrderMessage($orderNo, $this->smsConfig['order']);
            foreach ($mobile as $item) {
                $mobile = (string)$item;
                $results = $this->easySms->send($mobile, $message);
                $results = array_column($results, "result");
                if(!empty($results[0]['ActionStatus']) && $results[0]['ActionStatus'] == 'FAIL'){
                    throw new \Exception($results[0]['ErrorInfo'] ?? '');
                }
                $this->saveValidateCodeLog($mobile, $message->getContent() . $orderNo);
            }
            return true;
        } catch (NoGatewayAvailableException $e) {
            \Yii::error('短信发送失败:' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 发送订单退款短信通知
     * @param array $mobile [157.., 183..]
     * @param $orderNo
     * @return bool
     * @throws NoGatewayAvailableException
     * @throws \Exception
     */
    public function sendOrderRefundMessage(array $mobile, $orderNo)
    {
        if (count($mobile) != count($mobile, 1)) {
            throw new \Exception('手机号数组格式错误,请传入一维数组');
        }

        try {
            $message = new OrderRefundMessage($orderNo, $this->smsConfig['order_refund']);
            foreach ($mobile as $item) {
                $mobile = (string)$item;
                $results = $this->easySms->send($mobile, $message);
                $results = array_column($results, "result");
                if(!empty($results[0]['ActionStatus']) && $results[0]['ActionStatus'] == 'FAIL'){
                    throw new \Exception($results[0]['ErrorInfo'] ?? '');
                }
                $this->saveValidateCodeLog($mobile, $message->getContent() . $orderNo);
            }

            return true;
        } catch (NoGatewayAvailableException $e) {
            \Yii::error('短信发送失败:' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 存储短信发送日志
     * @param $target
     * @param $content
     * @return bool
     */
    private function saveValidateCodeLog($target, $content)
    {
        $log = new CoreValidateCodeLog();
        $log->target = $target;
        $log->content = $content;
        $res = $log->save();

        return $res;
    }

    /**
     * 检测短信验证码是否有效
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkValidateCode($mobile, $code)
    {
        $validateDate = date('Y-m-d H:i:s', time() - self::$validityMinutes * 60);
        $coreValidateCode = CoreValidateCode::find()->where([
            'target' => $mobile,
            'code' => $code,
            'is_validated' => CoreValidateCode::IS_VALIDATED_FALSE
        ])->andWhere(['>', 'created_at', $validateDate])->one();

        if ($coreValidateCode) {
            return true;
        }

        return false;
    }

    /**
     * 将验证码状态更新为 已使用
     * @param $mobile
     * @param $code
     * @return bool
     * @throws \Exception
     */
    public static function updateCodeStatus($mobile, $code)
    {
        $coreValidateCode = CoreValidateCode::find()->where([
            'target' => $mobile,
            'code' => $code,
        ])->one();

        if (!$coreValidateCode) {
            throw new \Exception('验证码不存在');
        }

        $coreValidateCode->is_validated = CoreValidateCode::IS_VALIDATED_TRUE;
        $res = $coreValidateCode->save();

        if (!$res) {
            throw new \Exception('验证码可用状态更新失败');
        }

        return true;
    }
}
