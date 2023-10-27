<?php
/**
 * Created By PhpStorm
 * Date: 2021/5/13
 * Time: 10:18 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\helpers;

class Json extends \yii\helpers\Json
{
    public static function decode($json, $asArray = true, $default = null)
    {
        if (!$json) {
            $res = $default;
        } else {
            $res = parent::decode($json, $asArray);
        }
        if (!$res) {
            $res = $default;
        }
        return $res;
    }
}
