<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\wxapp\forms\wx_app_config;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\forms\open3rd\ExtAppForm;
use app\models\Model;
use app\models\Option;

class PrivacyEditForm extends Model
{
    public $data; // @czs
    public $other; // @czs

    public function rules()
    {
        return [
            [['data', 'other'], 'safe'],
        ];
    }

    /**
     * 保存微信第三方隐私协议
     * @return array
     * @czs
     */
    public function savePrivacyInfo(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $this->data = (array)(is_string($this->data) ? \Yii::$app->serializer->decode($this->data) : $this->data);
        $this->other = (array)(is_string($this->other) ? \Yii::$app->serializer->decode($this->other) : $this->other);
        foreach ($this->other as $v){
            if(!empty($v['privacy_text'])){
                $this->data['setting_list'][] = [
                    'privacy_key' => $v['privacy_key'],
                    'privacy_text' => $v['privacy_text'],
                ];
            }
        }

        CommonOption::set(Option::NAME_WX_MINI_PRIVACY, $this->data, \Yii::$app->mall->id, Option::GROUP_APP);

        ExtAppForm::operatePrivacy(); // @czs 处理用户隐私协议
        ExtAppForm::operatePrivacy(1); // @czs 设置现网版本的用户协议

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功',
        ];
    }
}
