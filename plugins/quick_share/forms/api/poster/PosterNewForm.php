<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\quick_share\forms\api\poster;


use app\bootstrap\response\ApiCode;
use app\forms\api\poster\BasePoster;
use app\forms\api\poster\common\StyleGrafika;
use app\models\Model;
use app\plugins\quick_share\forms\common\CommonGoods;
use app\plugins\quick_share\models\Goods;

class PosterNewForm extends Model implements BasePoster
{
    public $style;
    public $typesetting;
    public $type;
    public $goods_id;
    public $color;

    public $id;

    public function rules()
    {
        return [
            [['style', 'typesetting'], 'required'],
            [['style', 'typesetting', 'type', 'id', 'goods_id'], 'integer'],
            [['color'], 'string'],
        ];
    }

    public function poster()
    {
        try {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $this->get()
            ];

        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
        }
    }

    public function get()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $class = $this->getClass($this->style);
        if ($this->id) {
            $share_goods = CommonGoods::getGoods($this->id);
            $goods = $share_goods->goods;
        }

        if ($this->goods_id) {
            $goods = Goods::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->goods_id,
            ]);
            $share_goods = $goods->quickShareGoods;
        }
        if (empty($goods)) {
            throw new \Exception('海报-商品不存在');
        }
        $class->typesetting = $this->typesetting;
        $class->type = $this->type;
        $class->color = $this->color;
        $class->goods = $goods;
        $class->other = $share_goods->share_pic ?? '';

        $class->extraModel = 'app\plugins\quick_share\forms\api\poster\PosterCustomize';
        return $class->build();
    }

    /**
     * @param int $key
     * @return StyleGrafika
     * @throws \Exception
     */
    private function getClass(int $key): StyleGrafika
    {
        $map = [
            1 => 'app\forms\api\poster\style\StyleOne',
            2 => 'app\forms\api\poster\style\StyleTwo',
            3 => 'app\forms\api\poster\style\StyleThree',
            4 => 'app\forms\api\poster\style\StyleFour',
        ];
        if (isset($map[$key]) && class_exists($map[$key])) {
            return new $map[$key];
        }
        throw new \Exception('调用错误');
    }
}