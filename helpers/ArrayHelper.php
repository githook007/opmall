<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/9/3
 * Time: 3:48 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * @param $array
     * @param $keys
     * @param null $default
     * @return array|mixed|null
     * 批量删除键值
     */
    public static function removeList(&$array, $keys, $default = null)
    {
        if (!is_array($keys)) {
            return $default;
        }
        $value = [];
        while (count($keys) > 1) {
            $key = array_shift($keys);
            $value[] = self::remove($array, $key, $default);
        }
        return $value;
    }

    public static function filter($array, $filters)
    {
        if (empty($filters)) {
            return $array;
        } else {
            return parent::filter($array, $filters);
        }
    }

    /**
     * @param array $original   原始数据
     * @param array $new        修改数据
     * @param object $object    数据模型对象
     * @return array|false|string
     * 对比数组值差异，记录修改日志
     */
    public function dataHandle($original, $new, $object){
        $fun = function ($original, $data) use(&$fun){
            if(is_array($original)){
                $sdf = false;
                foreach ($original as $key => $item){
                    if(isset($data[$key]) && $fun($item, $data[$key])){
                        $sdf = true;
                        break;
                    }
                }
                return $sdf;
            }else{
                return $original != $data;
            }
        };
        $arrayDiff = [];
        $copyNew = $new;
        foreach ($original as $key => $item){
            if(is_array($item) && isset($new[$key]) && $fun($item, $new[$key])){
                $arrayDiff[$key] = $item;
                unset($original[$key], $new[$key]);
            }
            if(!is_array($item) && isset($new[$key]) && !$item && $item != $new[$key]){
                $arrayDiff[$key] = $item;
                unset($original[$key], $new[$key]);
            }
        }
        $diffArr = array_diff($original, $new);
        if(!$diffArr && !$arrayDiff){
            return [];
        }
        $data = array_merge($diffArr, $arrayDiff);
        if(method_exists($object, 'handleLog')){
            $text = $object->handleLog($data, $copyNew);
            $text = rtrim($text, "，");
            $text = rtrim($text, ",");
        }else{
            $text = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return $text;
    }
}
