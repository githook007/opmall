<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/19
 * Time: 16:14
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\ecard\controllers\api;


use app\controllers\api\ApiController;
use app\bootstrap\response\ApiCode;
use app\forms\common\ecard\CommonEcard;
use app\models\EcardOrder;

class IndexController extends ApiController
{
    public function actionIndex()
    {
        try {
            $orderId = \Yii::$app->request->get('order_id');
            $ecardOrder = EcardOrder::findOne(['order_id' => $orderId]);
            $ecard = CommonEcard::getCommon()->getEcard($ecardOrder->ecard_id);
            return $this->asJson([
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => [
                    'content' => $ecard->content,
                    'name' => $ecard->name,
                ]
            ]);
        } catch (\Exception $exception) {
            return $this->asJson([
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ]);
        }
    }

    public function actionList()
    {
        try {
            $list = CommonEcard::getCommon()->getEcardList();
            return $this->asJson([
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => $list
            ]);
        } catch (\Exception $exception) {
            return $this->asJson([
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ]);
        }
    }
}
