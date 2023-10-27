<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\forms\api\goods;


use app\bootstrap\response\ApiCode;
use app\forms\common\poster\PosterConfigTrait;
use app\models\Model;

class PosterForm extends Model
{
    use PosterConfigTrait;
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
        ];
    }

    public function getDetail()
    {
        try {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'config' => $this->getConfig(),
                    'info' => $this->getAll(),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}