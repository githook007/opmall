<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\integral_mall\forms\api\coupon;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\integral_mall\models\IntegralMallCoupons;

class CouponForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function detail()
    {
        $detail = IntegralMallCoupons::find()->where([
            'id' => $this->id,
            'mall_id' => \Yii::$app->mall->id
        ])->with('coupon.cat', 'coupon.goods')->asArray()->one();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $detail
            ]
        ];
    }
}
