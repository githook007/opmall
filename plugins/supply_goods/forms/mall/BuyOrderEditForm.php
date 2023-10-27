<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\shopping\forms\common\CommonShopping;

class BuyOrderEditForm extends Model
{
    public $id;

    public function rules()
    {
        return [
            [['id'], 'integer']
        ];
    }

    public function add()
    {
        try {
            $common = new CommonShopping();
            $res = $common->buyList($this->id);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '添加成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
