<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/13
 * Time: 15:52
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\handlers;




class OrderPayedHandlerClass extends \app\handlers\orderHandler\OrderPayedHandlerClass
{
    protected function notice()
    {
        \Yii::error('--community notice--');
        $this->sendTemplate()->sendMpTemplate()->sendBuyPrompt()->setGoods()->addShareOrder();
        return $this;
    }

    protected function pay()
    {
        \Yii::error('--community pay--');
        $this->becomeJuniorByFirstPay()->addShareOrder()->becomeShare();
        return $this;
    }
}
