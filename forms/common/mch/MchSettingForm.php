<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\mch;


use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\models\Mall;
use app\models\Model;
use app\models\Option;

/**
 * @property Mall $mall
 */
class MchSettingForm extends Model
{
    public $isDefaultCashType = false;

    public function search($mallId = 0)
    {
        $mallId = $mallId ?: \Yii::$app->mall->id;
        $setting = CommonOption::get(Option::NAME_MCH_MALL_SETTING, $mallId, Option::GROUP_APP);
        $setting = CommonAppConfig::check($setting, $this->getDefault());

        $intArr = ['is_service', 'is_information', 'is_distance', 'is_goods_audit', 'is_confirm_order', 'is_mch_address'];
        foreach ($setting as $key => $item) {
            if (in_array($key, $intArr)) {
                $setting[$key] = (int)$item;
            }
        }

        foreach ($setting['form_data'] as &$item) {
            $item['is_required'] = (int)$item['is_required'];
        }
        unset($item);
        $setting['form_data'] = array_values($setting['form_data']);

        if (!isset($setting['cash_type'])) {
            if (!$this->isDefaultCashType) {
                $setting['cash_type'] = [];
            } else {
                // 默认支持微信提现
                $setting['cash_type'] = ['wx'];
            }
        }

        return $setting;
    }

    private function getDefault()
    {
//        $cash_type = [
//        {
//            label: '自动打款',
//            value: 'auto'
//        },
//        {
//            label: '微信',
//            value: 'wx'
//        },
//        {
//            label: '支付宝',
//            value: 'alipay'
//        },
//        {
//            label: '银行卡',
//            value: 'bank'
//        },
//        {
//            label: '余额',
//            value: 'balance'
//        },
//    ]
        return [
            'cash_type' => [],
            'desc' => '',//入驻协议
            'is_service' => 1,//是否开启客服图标
            'form_data' => [],
            'is_information' => 0,
            'is_distance' => 0,
            'status' => 0,
            'is_goods_audit' => 1,
            'is_confirm_order' => 1,
            'is_mch_address' => 0,
            'logo' => '',
            'copyright' => '',
            'copyright_url' => ''
        ];
    }
}
