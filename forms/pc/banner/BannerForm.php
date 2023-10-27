<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c)
 * author: opmall
 */
namespace app\forms\pc\banner;

use app\bootstrap\response\ApiCode;
use app\models\pc\Banner;
use app\models\Model;

class BannerForm extends Model
{
    public $page = 1;
    public $page_size = 10;
    public $title;
    public $pic_url;
    public $sort;
    public $page_url;
    public $id;

    public function rules()
    {
        return [
            [['id', 'sort'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['page_url', 'pic_url'], 'string', 'max' => 300],
        ];
    }

    //GET
    public function getList()
    {
        $query = Banner::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ]);

        $list = $query->page($pagination, $this->page_size, $this->page)
                ->orderBy('sort asc,id DESC')
                ->asArray()
                ->all();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    //DELETE
    public function destroy()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $id = $this->id;
        if (!$id) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '数据不存在或已删除',
            ];
        }
        Banner::updateAll(['is_delete' => 1,'deleted_at' => date('Y-m-d H:i:s')], [
            'id' => $id,
        ]);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => 'success'
        ];
    }

    //SAVE
    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if($this->id) {
            $model = Banner::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->id,
            ]);
        }
        if (empty($model)) {
            $model = new Banner();
        }

        $model->attributes = $this->attributes;
        $model->mall_id = \Yii::$app->mall->id;
        if ($model->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
