<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/13
 * Time: 16:21
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\handlers;



class SuccessHandlerClass extends \app\handlers\orderHandler\OrderPayedHandlerClass
{
    protected function notice()
    {
        \Yii::error('--community success--');
        $this->sendSms()->sendMail()->receiptPrint('pay');
        $this->sendSmsToUser();
        return $this;
    }

    protected function pay()
    {
        \Yii::error('--community success--');
        $this->saveResult();
        return $this;
    }

    public function addShareOrder($isSendTemplate = true)
    {
        return $this;
    }

}
