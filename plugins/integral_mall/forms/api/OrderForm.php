<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\api;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\integral_mall\models\Order;
use app\plugins\integral_mall\Plugin;

class OrderForm extends Model
{
    public $id;
    public $page;

    public function rules()
    {
        return [
            [['id', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $list = Order::find()->where([
            'user_id' => \Yii::$app->user->id,
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id,
            'sign' => (new Plugin())->getName()
        ])
            ->with(['detail.goods.goodsWarehouse', 'integralOrder'])
            ->orderBy(['created_at' => SORT_DESC])
            ->page($pagination)->asArray()->all();

        foreach ($list as $lKey => $lItem) {
            foreach ($lItem['detail'] as $dKey => $dItem) {
                $goodsInfo = \Yii::$app->serializer->decode($dItem['goods_info']);
                $picUrl = isset($goodsInfo['goods_attr']['pic_url']) ? $goodsInfo['goods_attr']['pic_url'] : '';
                $coverPic = isset($dItem['goods']['cover_pic']) ? $dItem['goods']['cover_pic'] : '';
                $goodsInfo['goods_attr']['pic_url'] = $picUrl ?: $coverPic;
                $goodsInfo['name'] = $dItem['goods']['goodsWarehouse']['name'];
                $list[$lKey]['detail'][$dKey]['goods_info'] = $goodsInfo;
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    public function detail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $detail = Order::find()->where([
            'id' => $this->id,
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id,
            'sign' => (new Plugin())->getName()
        ])
            ->with('detail', 'integralOrder')->asArray()->one();

        if (!$detail) {
            throw new \Exception('优惠券订单详情不存在');
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $detail,
            ]
        ];
    }
}
