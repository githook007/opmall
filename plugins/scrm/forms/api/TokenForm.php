<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 4:25 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\plugins\scrm\forms\Model;

class TokenForm extends Model
{
    public $app_key;
    public $app_secret;

    public function rules()
    {
        return [
            [['app_key', 'app_secret'], 'required'],
            [['app_key', 'app_secret'], 'trim'],
            [['app_key', 'app_secret'], 'string'],
        ];
    }

    public function getToken()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $model = new \app\plugins\scrm\forms\common\Token([
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
            ]);
            return $this->success([
                'access_token' => $model->accessToken()
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
