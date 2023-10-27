<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/10/27
 * Time: 4:53 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\template\order_pay_template;

use app\forms\common\template\tplmsg\AccountChange;

class AccountChangeInfo extends BaseInfo
{
    public const TPL_NAME = 'account_change_tpl';
    protected $key = 'store';
    protected $chineseName = '账户变动提醒';

    public function getSendClass()
    {
        return new AccountChange();
    }

    public function configAll()
    {
        $iconUrlPrefix = './statics/img/mall/tplmsg/';
        return [
            'aliapp' => [
                'local' => [
                    'name' => '提现成功',
                    'img_url' => $iconUrlPrefix . 'aliapp/withdraw_success_tpl.png'
                ]
            ]
        ];
    }
}