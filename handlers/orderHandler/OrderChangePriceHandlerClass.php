<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/8/13
 * Time: 16:07
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\handlers\orderHandler;


class OrderChangePriceHandlerClass extends BaseOrderHandler
{
    public function handle()
    {
        \Yii::error('--改价事件触发--');
        $this->addShareOrder();
    }
}
