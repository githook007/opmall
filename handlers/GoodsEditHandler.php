<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/23 16:31
 */


namespace app\handlers;


use app\events\OrderEvent;
use app\models\Goods;

class GoodsEditHandler extends HandlerBase
{
    /**
     * 事件处理
     */
    public function register()
    {
        \Yii::$app->on(Goods::EVENT_EDIT, function ($event) {
            /** @var OrderEvent $event */
        });
    }
}
