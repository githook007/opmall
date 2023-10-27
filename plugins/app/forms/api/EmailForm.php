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

use app\bootstrap\mail\SendMail;
use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\CoreValidateCode;

class EmailForm extends Model
{
    public $email;
    public $type; // czs  login - 登录； register - 注册；

    public function rules()
    {
        return [
            [['email'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => '邮箱',
        ];
    }

    public function sendEmail()
    {
        if (!$this->validate()) {
            throw new \Exception($this->getErrorMsg());
        }
        try {
            $code = rand(100000, 999999);
            $coreValidateCode = new CoreValidateCode();
            $coreValidateCode->target = $this->email;
            $coreValidateCode->code = strval($code);
            if (!$coreValidateCode->save()) {
                throw new \Exception($this->getErrorMsg($coreValidateCode));
            }
            $mailer = new SendMail();
            $mailer->mall = \Yii::$app->mall;
            $mailer->codedMsg($this->email, $code);
            if (!$coreValidateCode->save()) {
                throw new \Exception($this->getErrorMsg($coreValidateCode));
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '邮箱验证码已发送。',
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
