<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonCats;
use app\models\GoodsCatRelation;
use app\models\Model;
use app\plugins\miaosha\models\MiaoshaGoods;

class CatsForm extends Model
{
    public $page;

    public function rules()
    {
        return [
            [['page'], 'safe'],
            [['page'], 'default', "value" => 1]
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $goodsIds = MiaoshaGoods::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ])->groupBy('goods_id')->select('goods_id');

        $list = GoodsCatRelation::find()->where([
            'is_delete' => 0,
        ])
            ->andWhere(['goods_id' => $goodsIds])
            ->with('cat')
            ->page($pagination)
            ->groupBy('cat_id')
            ->asArray()->all();

        $list = array_map(function ($item) {
            return $item['cat'];
        }, $list);


        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
            ]
        ];
    }
}
