<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\page;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\ModelActiveRecord;
use app\models\PageIntro;
use app\models\User;

class PageIntroEditForm extends Model
{
    public $route;
    public $content;

    public function rules()
    {
        return [
            [['route',], 'required'],
            [['content', 'route'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'route' => '路由',
            'content' => '内容',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        ModelActiveRecord::$log = false;
        try {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            $mallId = $user->identity->is_super_admin ? 0 : \Yii::$app->mall->id;
            $model = PageIntro::find()->where([
                'mall_id' => $mallId,
                'route' => $this->route
            ])->one();
            if(!$model){
                $model = new PageIntro();
                $model->mall_id = $mallId;
                $model->route = $this->route;
            }
            $model->content = $this->content;
            if(!$model->save()){
                throw new \Exception($this->getErrorMsg($model));
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
