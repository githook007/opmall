<?php

namespace app\forms\common\poster;

use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */
class PosterConfigForm extends Model
{
    public static function default()
    {
        return [
            'goods' => [
                'poster_style' => ['1', '2', '3', '4'],
                'image_style' => ['1', '2', '3', '4', '5'],
            ],
        ];
    }

    public static function get()
    {
        return CommonOption::get(
            Option::NAME_POSTER_NEW,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            self::default()
        );
    }
}