<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\controllers\mall;

use app\bootstrap\response\ApiCode;
use app\plugins\Controller;
use app\plugins\supply_goods\models\SupplyGoodsWholesaler;

class IndexController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost){
                $url = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl.'?r=plugin/supply_goods/mall/mch-goods/mch-goods-list';
                $is_open = \Yii::$app->request->post('is_open');
                if ($is_open == 2){
                    $wholesaler = SupplyGoodsWholesaler::findOne(['user_id' => \Yii::$app->user->id]);
                    if (!$wholesaler){ // 去申请
                        $is_open = 3;
                        $url = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl.'?r=plugin/supply_goods/mall/wholesaler/edit';
                    }else{ // 已经申请过了  去展示信息
                        if ($wholesaler->status != 1){  // 审核中或者审核失败
                            $is_open = 4;
                            $url = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl.'?r=plugin/supply_goods/mall/wholesaler/index';
                        }
                    }
                }
                \Yii::$app->session->set('sourceType', $is_open);
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '请求成功',
                    'url' => $url,
                ];
            }
        } else {
            $is_open = \Yii::$app->request->get('is_open') ? \Yii::$app->request->get('is_open') : 0;
            \Yii::$app->session->set('sourceType', $is_open);
            return $this->render('index');
        }
    }
}
