<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\fxhb\forms\mall;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\plugins\fxhb\forms\common\CommonRecommend;

class RecommendForm extends Model
{
    public $data;

    public function rules()
    {
        return [
            [['data'], 'safe']
        ];
    }

    public function save()
    {
        try {
            $data = \Yii::$app->serializer->decode($this->data);

            $setting = CommonOption::set(
                Option::NAME_FXHB_RECOMMEND_SETTING,
                $data,
                \Yii::$app->mall->id,
                Option::GROUP_APP
            );

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    public function getSetting()
    {
        $form = new CommonRecommend();
        $setting = $form->getSetting();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting
            ]
        ];
    }
}