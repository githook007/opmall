<?php
namespace app\forms\pc\user;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\bootstrap\sms\Sms;
use app\validators\PhoneNumberValidator;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsForm extends Model
{
    public $mobile;
    public $code;

    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['mobile'], PhoneNumberValidator::className()],
            [['code'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'code' => '验证码',
        ];
    }

    public function code()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $sms = new Sms();
            $res = CommonOption::get(
                Option::NAME_SMS,
                \Yii::$app->mall->id,
                Option::GROUP_ADMIN
            );
            if(!$res || $res['status'] == 0) {
                throw new \Exception('验证码功能未开启');
            };
            $sms->sendCaptcha($this->mobile);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '验证码获取成功'
            ];
        } catch (\Exception $exception) {
            if($exception instanceof NoGatewayAvailableException) {
                $msg = '验证码配置错误';
            } else {
                $msg = $exception->getMessage();
            }

            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $msg
            ];
        }
    }
}
