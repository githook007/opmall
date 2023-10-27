<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\mall\goods;

use app\bootstrap\response\ApiCode;
use app\forms\common\goods\CommonGoods;
use app\forms\common\goods\GoodsBase;
use app\plugins\exchange\forms\common\CommonModel;

class CardGoodsForm extends GoodsBase
{
    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $common = CommonGoods::getCommon();
            $detail = $common->getGoodsDetail($this->id, false);
            $cardGoods = CommonModel::getCardGoods($this->id);
            if (!$cardGoods) {
                throw new \Exception('数据异常，插件商品不存在');
            }
            $detail = array_merge($detail, ['status' => intval($detail['status'])], $common->getDistrictPrice($detail['attr']));
            $detail['plugin_data']['library'] = [
                'library_name' => $cardGoods->library->name,
                'library_id' => (string)$cardGoods->library_id,
            ];
            if (!$detail) {
                throw new \Exception('请求失败');
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $detail,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ],
            ];
        }
    }
}
