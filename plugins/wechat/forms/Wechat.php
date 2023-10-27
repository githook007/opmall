<?php
/**
 * Created by PhpStorm
 * Date: 2020/11/25
 * Time: 9:28 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\wechat\forms;


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
