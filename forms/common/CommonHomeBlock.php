<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common;


use app\models\HomeBlock;

class CommonHomeBlock
{
    /**
     * 获取所有图片魔方
     * @return array
     */
    public static function getAll()
    {
        $list = HomeBlock::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ])->asArray()->all();


        return [
            'list' => $list,
        ];
    }
}
