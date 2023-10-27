<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\goods;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\forms\common\goods\CommonRecommendSettingForm;
use app\models\Model;
use app\models\Option;

class RecommendSettingForm extends Model
{
    public $data;

    public function getSetting()
    {
        $form = new CommonRecommendSettingForm();
        $setting = $form->getSetting();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting
            ]
        ];
    }

    public function save()
    {
        try {
            $data = \Yii::$app->serializer->decode($this->data);
            if ($data['goods']['goods_num'] > 10) {
                throw new \Exception('推荐商品显示数量最多10个');
            }
            if ($data['goods']['is_recommend_status'] && $data['goods']['goods_num'] < 1) {
                throw new \Exception('推荐商品显示数量最少1个');
            }
            $data['goods']['goods_num'] = (int)$data['goods']['goods_num'];

            $setting = CommonOption::set(
                Option::NAME_RECOMMEND_SETTING,
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
}