<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\api\poster;

use app\forms\api\poster\common\BaseConst;
use app\models\Model;

class PosterCustomize extends Model implements BaseConst
{
    public function traitQrcode($class)
    {
        return [
            ['id' => $class->goods->id, 'user_id' => \Yii::$app->user->id, 'mch_id' => $class->goods->mch_id],
            240,
            'plugins/mch/goods/goods',
        ];
    }
}