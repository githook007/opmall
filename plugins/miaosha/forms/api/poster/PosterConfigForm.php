<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\forms\api\poster;


use app\bootstrap\response\ApiCode;
use app\forms\common\poster\PosterConfigTrait;
use app\models\Model;
use app\plugins\miaosha\models\MiaoshaGoods;
use app\plugins\miaosha\Plugin;

class PosterConfigForm extends Model
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
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

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

    public function getExtra(): array
    {
        $miaosha = MiaoshaGoods::find()->where([
            'goods_id' => $this->goods_id
        ])->one();
        $model = new PosterCustomize();
        $data = $model->traitMultiMapContent((object)['other' => $miaosha]);

        return [
            'extra_multiMap' => $this->formatType($data),
        ];
    }

    public function getPlugin(): array
    {
        return [
            'sign' => (new Plugin())->getName(),
        ];
    }
}