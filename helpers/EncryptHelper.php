<?php
/**
 * Created by PhpStorm
 * Date: 2021/1/28
 * Time: 9:52 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\helpers;

use yii\base\BaseObject;

class EncryptHelper extends BaseObject
{
    public $key = 'op';

    /**
     * @param $data
     * @param $key
     * @param string $string
     * @return string
     * 异或运算
     */
    public function getXOR($data, $key, $string = '')
    {
        $len = strlen($data);
        $len2 = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            $j = $i % $len2;
            $string .= ($data[$i]) ^ ($key[$j]);
        }
        return $string;
    }

    /**
     * @param $data
     * @return string
     * 简单加密
     */
    public static function encrypt($data)
    {
        $class = new self();
        $xorData = $class->getXOR($data, $class->key);
        return base64_encode($class->key . $xorData);
    }

    /**
     * @param $data
     * 简单解密
     */
    public static function decrypt($data)
    {
        $class = new self();
        $data = base64_decode($data);
        $len = strlen($class->key);
        $key = substr($data, 0, $len);
        if ($key !== $class->key) {
            throw new \Exception('错误的字符串，无法解密');
        }
        $xorData = substr($data, $len);
        return $class->getXOR($xorData, $class->key);
    }

    //加密函数
    static function lock_url($txt, $key = 'ws')
    {
        $txt = $txt . $key;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0, 64);
        $ch = $chars[$nh];
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = base64_encode($txt);
        $tmp = '';
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars, $txt[$i]) + ord($mdKey[$k++])) % 64;
            $tmp .= $chars[$j];
        }
        return urlencode(base64_encode($ch . $tmp));
    }

    //解密函数
    static function unlock_url($txt, $key = 'ws')
    {
        $txt = base64_decode(urldecode($txt));
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars, $ch);
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = substr($txt, 1);
        $tmp = '';
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
            while ($j < 0) $j += 64;
            $tmp .= $chars[$j];
        }
        return trim(base64_decode($tmp), $key);
    }

    /**
     * @param $string
     * @param string $key
     * @param bool $decode
     * @param int $expiry
     * @return false|string
     * @czs 加密解密
     */
    public static function authCode($string, $key = '', $decode = true, $expiry = 0) {
        $length = 4;
        $key = md5($key);
        $key1 = md5(substr($key, 0, 16));
        $key2 = md5(substr($key, 16, 16));
        $key3 = $decode ? substr($string, 0, $length) : substr(md5(microtime()), -$length);

        $crypt_key = $key1 . md5($key1 . $key3);
        $key_length = strlen($crypt_key);

        $string = $decode ? base64_decode(substr($string, $length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $key2), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rnd_key = array();
        for ($i = 0; $i <= 255; $i++) {
            $rnd_key[$i] = ord($crypt_key[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rnd_key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($decode) {
            if (
                (substr($result, 0, 10) == 0 || intval(substr($result, 0, 10)) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26) . $key2), 0, 16)
            ) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $key3 . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * 将短字符串转为数字
     * @param string $string
     * @return int 数字
     */
    public static function getNum($string){
        $codes = "0ghijklmnopvwqrstuxyz12ST3459ABCDJKL6cd78MNOPQRUVWEFGHIXYZabef";
        $num = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            $n = strlen($string) - $i - 1;
            $pos = strpos($codes, $string[$i]);
            $num += $pos * pow(62, $n);
        }
        return $num;
    }

    /**
     * 将数字转为短字符串
     * @param int $number 数字
     * @return string 短字符串
     */
    public static function generateCode($number){
        $out = "";
        $codes = "0ghijklmnopvwqrstuxyz12ST3459ABCDJKL6cd78MNOPQRUVWEFGHIXYZabef";
        while ($number > 61) {
            $m = $number % 62;
            $out = $codes[$m] . $out;
            $number = ($number - $m) / 62;
        }
        return $codes[$number] . $out;
    }
}
