<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/4
 * Time: 11:39
 */

namespace app\plugins\bonus\handlers;

use app\handlers\HandlerBase;
use app\plugins\bonus\events\CaptainEvent;
use app\plugins\bonus\models\BonusCaptain;

class RemoveCaptainHandler extends HandlerBase
{
    public function register()
    {
        \Yii::$app->on(BonusCaptain::EVENT_REMOVE, function ($event) {
            /**
             * @var CaptainEvent $event
             */

        });
    }
}