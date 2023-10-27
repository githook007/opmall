<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/24
 * Time: 10:49 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\wechat\config;


class Wechat extends \luweiss\Wechat\Wechat
{
    public $name;
    public $logo;
    public $qrcode;

    public function getInfo()
    {
        return [
            'name' => $this->name,
            'logo' => $this->logo,
            'qrcode' => $this->qrcode,
        ];
    }
}