<?php
/**
 * Created By PhpStorm
 * Date: 2021/7/13
 * Time: 11:30 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\common\CommonQrCode;
use app\plugins\scrm\forms\Model;

class CodeForm extends Model
{
    public $scene;
    public $path;

    public function rules()
    {
        return [
            [['path', 'scene'], 'trim'],
            [['path', 'scene'], 'string'],
        ];
    }

    public function getCode()
    {
        $common = new CommonQrCode([
            'appPlatform' => APP_PLATFORM_WXAPP
        ]);
        $scene = '';
        if ($this->scene) {
            $temp = explode('&', $this->scene);
            $scene = [];
            foreach ($temp as $item) {
                $t = explode('=', $item);
                if (count($t) == 2) {
                    $scene[$t[0]] = $t[1];
                }
            }
        }
        $data = $common->getQrCode($scene, 430, ltrim($this->path, '/'));
        return $this->success($data);
    }
}
