<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\fxhb\forms\common;


use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class CommonRecommend extends Model
{
    public function getDefault()
    {
        return [
            'fxhb' => [
                'is_recommend_status' => 1,
                'is_custom' => 0,
                'goods_list' => []
            ]
        ];
    }

    public function getSetting()
    {
        $setting = CommonOption::get(
            Option::NAME_FXHB_RECOMMEND_SETTING,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            $this->getDefault()
        );

        foreach ($setting as $key => &$item) {
            if (isset($item['is_recommend_status'])) {
                $item['is_recommend_status'] = (int)$item['is_recommend_status'];
            }
            if (isset($item['is_custom'])) {
                $item['is_custom'] = (int)$item['is_custom'];
            }
        }

        return $setting;
    }
}