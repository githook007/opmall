<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/1
 * Time: 15:35
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\forms\api\cash;


use app\bootstrap\response\ApiCode;
use app\forms\api\finance\BaseFinanceConfig;
use app\forms\common\template\TemplateList;
use app\plugins\community\forms\common\CommonMiddleman;
use app\plugins\community\forms\common\CommonSetting;

class CommunityFinanceConfig extends BaseFinanceConfig
{
    public function config()
    {
        $setting = CommonSetting::getCommon()->getSetting();
        $common = CommonMiddleman::getCommon();
        $middleman = $common->getConfig(\Yii::$app->user->id);
        if (!$middleman || $middleman->status != 1) {
            throw new \Exception('当前用户不是团长');
        }
        $tpl = ['withdraw_error_tpl', 'withdraw_success_tpl'];
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'config' => [
                    'pay_type' => $setting['pay_type'],
                    'min_money' => $setting['min_money'],
                    'cash_service_charge' => $setting['cash_service_charge'],
                    'free_cash_min' => $setting['free_cash_min'],
                    'money' => $middleman->money,
                ],
                'template_message' => TemplateList::getInstance()->getTemplate(\Yii::$app->appPlatform, $tpl),
            ],
        ];
    }
}
