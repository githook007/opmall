<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/7
 * Time: 9:11 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\forms\common\goods\CommonGoodsList;
use app\models\Goods;

class GetGoodsForm extends Model
{
    public $keyword;
    public $page;

    public function rules()
    {
        return [
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
            ['page', 'integer'],
            ['page', 'default', 'value' => 1]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $common = new CommonGoodsList();
            $common->attributes = $this->attributes;
            $common->relations = ['goodsWarehouse', 'mallGoods'];
            $common->keyword = $this->keyword;
            $common->status = 1;
            $common->sign = ['mch', ''];
            $common->mch_id = 0;
            /* @var Goods[] $goodsList */
            $goodsList = $common->search();
            $newList = [];
            foreach ($goodsList as $goods) {
                $newItem = [
                    'id' => $goods->id,
                    'goodsWarehouse' => $goods->goodsWarehouse,
                    'name' => $goods->goodsWarehouse->name,
                    'price' => $goods->price
                ];
                if ($goods->mallGoods) {
                    $newItem = array_merge($newItem, [
                        'is_negotiable' => $goods->mallGoods->is_negotiable
                    ]);
                }
                $newList[] = $newItem;
            }
            return $this->success([
                'list' => $newList,
                'pagination' => $common->pagination
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
