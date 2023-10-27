<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\gift\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\mall\goods\BaseGoodsEdit;
use app\models\Mall;
use app\plugins\gift\Plugin;

/**
 * @property Mall $mall;
 */
class GoodsEditForm extends BaseGoodsEdit
{
    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->attrValidator();
            $this->setGoods();
            $this->setAttr();
            $this->setCard();
            $this->setCoupon();
            $this->setGoodsService();
            $this->setListener();
            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    protected function setGoodsSign()
    {
        return (new Plugin())->getName();
    }
}
