<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/26
 * Time: 17:40
 */

namespace app\handlers;

use app\forms\common\CommonBuyPrompt;

class AppBuyMessageHandler extends HandlerBase
{

    /**
     * 事件处理注册
     */
    public function register()
    {
        \Yii::$app->on(\Yii::$app->appMessage::EVENT_APP_MESSAGE_REQUEST, function ($event) {
            \Yii::$app->appMessage->push('buy_data', (new CommonBuyPrompt())->get());
        });
    }
}
