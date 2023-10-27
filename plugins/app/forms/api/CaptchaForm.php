<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/14
 * Time: 4:11 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\api;

use app\bootstrap\newsms\Sms;
use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\models\CoreValidateCode;
use app\models\Model;
use app\plugins\app\models\LoginForm;
use Overtrue\EasySms\Message;

class CaptchaForm extends Model
{
    public $mobile;
    public $type; // czs  login - 登录； register - 注册；

    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['type'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
        ];
    }

    public function sendSmsCaptcha()
    {
        if (!$this->validate()) {
            throw new \Exception($this->getErrorMsg());
        }
        try {
//            if ($this->type == 'login') {
//                $platform = (new LoginForm())->getUser($this->mobile);
//                if(!$platform){
//                    throw new \Exception('手机号未注册');
//                }
//            } elseif ($this->type == 'register') {
//                $platform = (new LoginForm())->getUser($this->mobile);
//                if($platform){
//                    throw new \Exception('手机号已注册');
//                }
//            } elseif ($this->type == 'modifyPassword') {
//                $platform = (new LoginForm())->getUser($this->mobile);
//                if(!$platform){
//                    throw new \Exception('手机号未注册');
//                }
//            }
            $code = rand(100000, 999999);
            $smsConfig = CommonAppConfig::getSmsConfig();
            if (!$smsConfig
                || empty($smsConfig['status'])
                || $smsConfig['status'] == 0
                || empty($smsConfig['captcha']['template_id'])) {
                throw new \Exception('短信信息尚未配置');
            }
            $coreValidateCode = new CoreValidateCode();
            $coreValidateCode->target = $this->mobile;
            $coreValidateCode->code = strval($code);
            if (!$coreValidateCode->save()) {
                throw new \Exception($this->getErrorMsg($coreValidateCode));
            }
            \Yii::$app->sms->module(Sms::MODULE_MALL)->send($this->mobile, new Message([
                'content' => null,
                'template' => $smsConfig['captcha']['template_id'],
                'data' => [
                    $smsConfig['captcha']['template_variable'] => $code,
                ],
            ]));
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '短信验证码已发送。',
                'data' => [
                    'validate_code_id' => $coreValidateCode->id,
                ],
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }
}
