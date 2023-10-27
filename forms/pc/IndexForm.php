<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c)
 * author: opmall
 */
namespace app\forms\pc;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\GoodsCats;
use yii\helpers\ArrayHelper;

class IndexForm
{
    public function getCatList(){
        $data = GoodsCats::find()->where(["mall_id" => \Yii::$app->mall->id, "mch_id" => 0, "parent_id" => 0, "status" => 1, "is_delete" => 0, "is_show" => 1])
            ->select("id,name")
            ->orderBy("sort asc,id desc")->asArray()->all();
        return ["code" => ApiCode::CODE_SUCCESS, "data" => $data];
    }

    /* 迭代无限极分类 */
    public static function iterationTree($list, $id = 'id', $pid = 'pid', $root = 0, $child = [])
    {
        $data = array();
        foreach ($list as $key => $val) {
            if ($val[$pid] == $root) {
                //获取当前$pid所有子类
                unset($list[$key]);
                if (!empty($list)) {
                    $child = self::iterationTree($list, $id, $pid, $val[$id], $child);
                    if (!empty($child)) {
                        $val['children'] = $child;
                    }
                }
                $data[] = $val;
            }
        }
        return $data;
    }
}
