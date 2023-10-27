<?php
/**
 * Created By PhpStorm
 * Date: 2021/7/9
 * Time: 2:54 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\diy;

use app\models\Model;

class CheckComponent extends Model
{
    public static function check($array)
    {
        $permission = \Yii::$app->mall->role->getAccountPermission();
        if ($permission !== true) {
            $newList = [];
            foreach ($array as $item) {
                if (isset($item['list'])) {
                    $list = [];
                    foreach ($item['list'] as $value) {
                        if (!$permission || (isset($value['key']) && !in_array($value['key'], $permission))) {
                            continue;
                        }
                        $list[] = $value;
                    }
                    if (count($list) > 0) {
                        $newItem = $item;
                        $newItem['list'] = $list;
                        $newList[] = $newItem;
                    }
                } else {
                    $newItem = $item;
                    $newList[] = $newItem;
                }
            }
        } else {
            $newList = $array;
        }
        return $newList;
    }
}
