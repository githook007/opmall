<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\goods;

use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class CommonRecommendSettingForm extends Model
{
    public function getSetting()
    {
        $setting = CommonOption::get(
            Option::NAME_RECOMMEND_SETTING,
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

    public function getDefault()
    {
        return [
            'goods' => [
                'is_recommend_status' => 1,
                'goods_num' => 6
            ],
            'order_pay' => [
                'is_recommend_status' => 1,
                'is_custom' => 0,
                'goods_list' => []
            ],
            'order_comment' => [
                'is_recommend_status' => 1,
                'is_custom' => 0,
                'goods_list' => []
            ],
            'cart' => [
                'is_recommend_status' => 1,
                'is_custom' => 0,
                'goods_list' => []
            ],
        ];
    }
}