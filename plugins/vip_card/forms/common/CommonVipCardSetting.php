<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/8/27
 * Time: 13:44
 */

namespace app\plugins\vip_card\forms\common;

use app\forms\common\template\TemplateList;
use app\models\Model;
use app\plugins\vip_card\models\VipCardSetting;
use yii\helpers\ArrayHelper;

class CommonVipCardSetting extends Model
{
    public function getSetting()
    {
        $setting = VipCardSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        if ($setting) {
            $setting = ArrayHelper::toArray($setting);
            $setting['payment_type'] = json_decode($setting['payment_type']) ?: $this->getDefault()['payment_type'];
            $setting['rules'] = json_decode($setting['rules']) ?: $this->getDefault()['rules'];
            $setting['form'] = json_decode($setting['form']) ?: $this->getDefault()['form'];
            if(\Yii::$app->appPlatform == APP_PLATFORM_APP){ // @czs 针对app的优化
                if(!empty($setting['form']->vip_icon_list)) {
                    $tmp = [];
                    foreach ($setting['form']->vip_icon_list as $item){
                        $tmp = array_merge($tmp, $item);
                    }
                    $setting['form']->vip_icon_list = $tmp;
                }
            }
            $setting['shareLevelList'] = json_decode($setting['share_level']) ?: $this->getDefault()['shareLevelList'];
            $card = CommonVip::getCommon()->getMainCard();
            $setting['name'] = $card->name ?? $this->getDefault()['name'];
            $orderForm = json_decode($setting['order_form'], true) ?? $this->getDefault()['order_form'];
            foreach ($orderForm as &$item) {
                if (isset($item['is_required'])) {
                    $item['is_required'] = $item['is_required'] == 1 ? 1 : 0;
                }
            }
            unset($item);
            $setting['order_form'] = $orderForm;
        } else {
            $setting = $this->getDefault();
        }
        try {
            $templateMessage = TemplateList::getInstance()->getTemplate(
                \Yii::$app->appPlatform,
                ['vip_card_remind', 'order_pay_tpl']
            );
            $setting['template_message'] = $templateMessage;
        } catch (\Exception $exception) {
            $setting['template_message'] = [];
        }

        return $setting;
    }

    public function getDefault()
    {
        return [
            'is_vip_card' => 0,
            'payment_type' => ['online_pay'],
            'is_share' => 0,
            'is_sms' => 0,
            'is_mail' => 0,
            'is_agreement' => 0,
            'agreement_title' => '',
            'agreement_content' => '',
            'form' => new \stdClass(),
            'rules' => new \stdClass(),
            'is_buy_become_share' => 0,
            'share_type' => 1,
            'share_commission_first' => 0,
            'share_commission_second' => 0,
            'share_commission_third' => 0,
            'name' => '超级会员卡',
            'shareLevelList' => [],
            'is_order_form' => 0,
            'order_form' => []
        ];
    }
}
