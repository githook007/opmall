<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/11
 * Time: 15:28
 */

namespace app\plugins\ttapp\forms;

use app\bootstrap\payment\PaymentException;
use app\forms\common\transfer\BaseTransfer;
use app\plugins\wxapp\Plugin;
use luweiss\Wechat\WechatException;

class TtTransfer extends BaseTransfer
{
    public function transfer($paymentTransfer, $user)
    {
        throw new \Exception('头条用户暂不支持提现功能，请使用其他方式提现~');
    }
}