<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/5/16
 * Time: 10:49
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\mall\app_page;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonQrCode;
use app\models\Model;

class AppPageForm extends Model
{
    public $path;
    public $params;
    public $platform;

    public function rules()
    {
        return [
            [['path', 'params', 'platform'], 'trim'],
            [['path'], 'required'],
            ['platform', 'string'],
            ['platform', 'default', 'value' => 'all'],
            [['params'], 'safe']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $qrcode = new CommonQrCode();
            $qrcode->appPlatform = $this->platform;
            if ($this->params) {
                $this->params = json_decode($this->params, true);
            }
            $list = $qrcode->getQrCode($this->params, 430, $this->path);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => $list
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
