<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\common;


use app\models\Mall;
use app\models\Model;
use app\plugins\integral_mall\models\IntegralMallBanners;

/**
 * @property Mall $mall
 */
class BannerListForm extends Model
{
    public $mall;

    public function search()
    {
        if (!$this->mall) {
            $this->mall = \Yii::$app->mall;
        }
        $query = IntegralMallBanners::find()->where([
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
        ]);

        $list = $query->with('banner')
            ->orderBy('id DESC')
            ->asArray()
            ->all();

        $list = array_map(function ($item) {
            return $item['banner'];
        }, $list);


        return $list;
    }
}
