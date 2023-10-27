<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\mch;


use app\forms\common\version\Compatible;
use app\models\Mall;
use app\models\Model;
use app\plugins\mch\models\MchSetting;
use yii\helpers\ArrayHelper;

/**
 * @property Mall $mall
 */
class SettingForm extends Model
{
    public $mch_id;

    public function rules()
    {
        return [
            [['mch_id'], 'integer']
        ];
    }

    public function search()
    {
        $mchId = $this->mch_id ?: \Yii::$app->user->identity->mch_id;
        $setting = MchSetting::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'mch_id' => $mchId,
        ])->one();

        if (!$setting) {
            $setting = $this->getDefault();
        }
        $setting = ArrayHelper::toArray($setting);
        $setting['send_type'] = Compatible::getInstance()->sendType($setting['send_type']);
        if (($key = array_search('city', $setting['send_type'])) !== false) {
            unset($setting['send_type'][$key]);
        }
        return $setting ?: [];
    }

    public function getDefault()
    {
        return [
            'is_share' => 0,
            'is_sms' => 0,
            'is_mail' => 0,
            'is_print' => 0,
            'is_territorial_limitation' => 0,
            'send_type' => ['express'],
            'is_web_service' => 0,
            'web_service_type' => 1, // @czs 客服外链类型 1：其它客服，2：企业微信客服
            'enterprise_wechat_id' => '', // 客服外链 的企业微信id
            'web_service_url' => '',
            'web_service_pic' => ''
        ];
    }
}
