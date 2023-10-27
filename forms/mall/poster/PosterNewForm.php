<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\forms\mall\poster;

use Algorithm\sort;
use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\forms\common\poster\PosterConfigForm;
use app\models\Model;
use app\models\Option;

class PosterNewForm extends Model
{
    public $form;

    public function rules()
    {
        return [
            [['form'], 'string'],
            [['form'], 'required'],
        ];
    }

    public function get()
    {
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'detail' => PosterConfigForm::get()
            ],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }


        try {
            $form = \yii\helpers\BaseJson::decode($this->form);
            if (empty($form['goods']['poster_style'])) {
                throw new \Exception('海报样式不能为空');
            }
            sort($form['goods']['poster_style']);
            if (empty($form['goods']['image_style'])) {
                throw new \Exception('商品图数量不能为空');
            }
            sort($form['goods']['image_style']);
            CommonOption::set(
                Option::NAME_POSTER_NEW,
                $form,
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
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ]
            ];
        }
    }

}