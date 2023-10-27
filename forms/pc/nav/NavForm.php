<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\nav;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\pc\Nav;

class NavForm extends Model
{
    public $limit;
    public $id;
    public $status;

    public function rules()
    {
        return [
            [['id', 'status', 'limit'], 'integer'],
            [['limit'], 'default', 'value' => 20],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = Nav::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ]);//->keyword($this->keyword, ['like', 'name', $this->keyword]);

        $list = $query->page($pagination, $this->limit)
            ->orderBy('sort ASC,id desc')
            ->asArray()->all();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    public function save($params){
        if(!empty($params['id'])){
            $nav = Nav::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $params['id']]);
        }
        if(empty($nav)){
            $nav = new Nav();
        }
        $nav->mall_id = \Yii::$app->mall->id;
        $nav->url = $params['url'];
        $nav->name = $params['name'];
        $nav->sort = $params['sort'];
        $nav->open_type = isset($params['open_type']) ? $params['open_type'] : "1";
        $nav->status = isset($params['status']) ? $params['status'] : 1;
        $res = $nav->save();
        if (!$res) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $this->getErrorMsg($nav),
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功',
        ];
    }

    public function destroy()
    {
        try {
            $Nav = Nav::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id]);

            if (!$Nav) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '数据异常,该条数据不存在'
                ];
            }

            $Nav->is_delete = 1;
            $res = $Nav->save();

            if (!$res) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $this->getErrorMsg($Nav),
                ];
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '删除成功',
            ];

        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
