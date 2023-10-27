<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\models\Model;
use app\plugins\fxhb\models\FxhbActivity;

class DiyModalForm extends Model
{
    public function getModal()
    {
        try {
            // 暂时只有裂变红包
            return \Yii::$app->plugin->getPlugin('fxhb')->getHomePage('api');
        } catch (\Exception $exception) {
            return [];
        }
    }
}
