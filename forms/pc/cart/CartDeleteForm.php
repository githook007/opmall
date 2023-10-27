<?php

namespace app\forms\pc\cart;

use app\bootstrap\response\ApiCode;
use app\events\CartEvent;
use app\models\Cart;
use app\models\Model;

class CartDeleteForm extends Model
{
    public $cart_id_list;

    public function rules()
    {
        return [
            [['cart_id_list'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        try {
            Cart::cacheStatusSet(true);
            $this->cart_id_list = \Yii::$app->serializer->decode($this->cart_id_list);
            Cart::updateAll(['is_delete' => 1, 'deleted_at' => date('Y-m-d H:i:s')], [
                'id' => $this->cart_id_list,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id,
                'user_id' => \Yii::$app->user->id,
            ]);
            //购物单
            \Yii::$app->trigger(Cart::EVENT_CART_DESTROY, new CartEvent(['cartIds' => $this->cart_id_list]));
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => 'success',
            ];
        } catch (\Exception  $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    public function __destruct()
    {
        Cart::cacheStatusSet(false);
    }

    public function delAll(){
        try {
            Cart::cacheStatusSet(true);
            $model = Cart::find()->where([
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id,
                'user_id' => \Yii::$app->user->id,
            ])->asArray()->all();
            if($model) {
                Cart::updateAll(['is_delete' => 1, 'deleted_at' => date('Y-m-d H:i:s')], [
                    'is_delete' => 0,
                    'mall_id' => \Yii::$app->mall->id,
                    'user_id' => \Yii::$app->user->id,
                ]);
                $cart_id_list = [];
                foreach($model as $item){
                    $cart_id_list[] = $item['id'];
                }
                //购物单
                \Yii::$app->trigger(Cart::EVENT_CART_DESTROY, new CartEvent(['cartIds' => $cart_id_list]));
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '清空购物车成功',
                ];
            }
        } catch (\Exception  $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
