<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/27
 * Time: 17:44
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\api;


use app\bootstrap\response\ApiCode;
use app\forms\common\goods\CommonGoodsList;
use app\forms\common\goods\CommonRecommendSettingForm;
use app\models\Goods;
use app\models\Mall;
use app\models\Model;

/**
 * @property Mall $mall
 */
class RecommendForm extends Model
{
    public $mall;
    public $goods_id;
    public $type;

    public function rules()
    {
        return [
            ['goods_id', 'integer'],
            [['type'], 'string'],
            [['type'], 'default', 'value' => 'goods']
        ];
    }

    public function getNewList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        
        try {
            $form = new CommonRecommendSettingForm();
            $setting = $form->getSetting();

            if (!isset($setting[$this->type])) {
                throw new \Exception('type参数错误:goods|order_pay|order_comment|fxhb|cart');
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'list' => $this->getGoodsList($setting[$this->type], $this->type)
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    private function getGoodsList($item, $key)
    {
        if ($item['is_recommend_status'] == 0) {
            return [];
        }

        $goodsIds = [];
        $form = new CommonGoodsList();
        if ($key == 'goods') {
            /** @var Goods $goods */
            $goods = Goods::find()->with('goodsWarehouse.cats')->where([
                'id' => $this->goods_id,
                'mall_id' => \Yii::$app->mall->id
            ])->one();
            if ($goods) {
                $form->cat_id = array_column($goods->goodsWarehouse->goodsCatRelation, 'cat_id');
            }
            $form->limit = $item['goods_num'];
            $form->exceptSelf = $this->goods_id;
        } else {
            if ($item['is_custom'] == 1) {
                // 推荐商品自定义
                foreach ($item['goods_list'] as $gItem) {
                    if (!in_array($gItem['id'], $goodsIds)) {
                        $goodsIds[] = $gItem['id'];
                    }
                }
                $form->goods_id = $goodsIds;
                $form->limit = count($goodsIds);
            } else {
                // 获取商品列表排序前10件商品
                $form->limit = 10;
                $form->sort = 1;
            }
        }

        $form->status = 1;
        $form->sign = ['mch', ''];
        $form->is_show = 1;
        $form->relations = ['goodsWarehouse', 'mallGoods', 'attr.memberPrice'];
        $list = $form->getList();

        // 商品重新排序
        $newList = [];
        if (isset($item['is_custom']) && $item['is_custom']) {
            foreach ($goodsIds as $id) {
                foreach ($list as $item) {
                    if ($item['id'] == $id) {
                        $newList[] = $item;
                    }
                }
            }
        } else {
            $newList = $list;
        }

        return $newList;
    }
}
