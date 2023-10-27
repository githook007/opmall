<?php
/**
 * Created by PhpStorm.
 * User: chenzs
 * Date: 2019/10/19
 * Time: 14:17
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\mall\share;

use app\bootstrap\response\ApiCode;
use app\models\GoodsCatRelation;
use app\models\GoodsWarehouse;
use app\models\Model;
use app\models\ShareLevelGoods;
use app\models\ShareSetting;

class GoodsForm extends Model
{
    public $search;

    public function rules()
    {
        return [
            [['search'], 'safe'],
        ];
    }

    public function getGoods()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $become_condition = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::BECOME_CONDITION, 0);

            if(!in_array($become_condition, [2, 3])){
                $data = [];
            }else{
                $data = $this->getData($pagination);
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                'data' => [
                    "list" => $data,
                    "pagination" => $pagination ?? null
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function getData(&$pagination){
        $share_goods_status = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::SHARE_GOODS_STATUS, 0);
        $search = (array)\Yii::$app->serializer->decode($this->search);

        $shareLevelGoods = ShareLevelGoods::find()->where([
            "mall_id" => \Yii::$app->mall->id,
            "is_delete" => 0
        ])
            ->keyword(!empty($search['id']), ["!=", "level_id", $search['id']])
            ->select("goods_warehouse_id");

        if($share_goods_status == 2) { // 固定商品
            $goodsId = (array)ShareSetting::get(\Yii::$app->mall->id, ShareSetting::SHARE_GOODS_WAREHOUSE_ID, 0);
            $tempGoods = $shareLevelGoods->column();
            $goodsId = array_diff($goodsId, $tempGoods);

            $where = ['id' => $goodsId];
        }elseif($share_goods_status == 3){ // 分类
            $catIdList = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::CAT_LIST);
            if (!$catIdList) {
                return [];
            }
            $goodsId = GoodsCatRelation::find()->where(['cat_id' => $catIdList, 'is_delete' => 0])
                ->select('goods_warehouse_id')->column();
            $tempGoods = $shareLevelGoods->column();
            $goodsId = array_diff($goodsId, $tempGoods);

            $where = ['id' => $goodsId];
        }else{ // 所有
            $where = ['not in', 'id', $shareLevelGoods];
        }

        $goodsList = GoodsWarehouse::find()->where([
            'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id,
        ])->keyword(!empty($search['keyword']), ["like", "name", $search['keyword']])
            ->andWhere($where)
            ->page($pagination)
            ->all();
        $data = [];
        /** @var GoodsWarehouse $goods */
        foreach ($goodsList as $goods) {
            $data[] = [
                'id' => $goods->id,
                'name' => $goods->name,
                'cover_pic' => $goods->cover_pic,
            ];
        }

        return $data;
    }
}