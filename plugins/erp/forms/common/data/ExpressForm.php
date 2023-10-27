<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\erp\forms\common\data;

use app\models\Model;
use app\plugins\erp\forms\common\api\ServeHttp;
use app\plugins\erp\forms\common\RequestForm;
use app\plugins\erp\models\ErpOrder;
use yii\helpers\Json;

class ExpressForm extends Model
{
    public $orderNo;

    public function queryLogistic(): array
    {
        try{
            $erpOrder = ErpOrder::findOne(['seller_no' => $this->orderNo, 'is_delete' => 0]);
            if(!$erpOrder){
                \Yii::warning("erp订单数据不存在");
                return [];
            }
            $form = RequestForm::getInstance();
            $params = Json::decode($erpOrder->params);
            $requestParams = [
                'shop_id' => $params['shop_id'] ?? $form->getApiObj()->shop_id,
                'so_ids' => [$erpOrder->seller_no]
            ];
            $res = $form->api(ServeHttp::QUERY_LOGISTIC, $requestParams);
            if ($res['code'] != 0) {
                throw new \Exception($res['msg']);
            }
            return $res['data'];
        }catch (\Exception $e){
            \Yii::warning($e);
            return [];
        }
    }
}
