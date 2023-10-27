<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/11/4
 * Time: 16:48
 */

namespace app\forms\mall\pay_type_setting;

/**
 * Class CommonPayType
 * @package app\forms\mall\pay_type_setting
 */
class CommonPayType
{
    /**
     * @param $platform
     * @return mixed
     * @throws \Exception
     */
    public static function get($platform)
    {
        $setting = (new PayTypeSettingForm())->getDetail()['data']['option'];
        if (!isset($setting[$platform])) {
            throw new \Exception('获取支付方式失败');
        }
        return $setting[$platform];
    }
}
