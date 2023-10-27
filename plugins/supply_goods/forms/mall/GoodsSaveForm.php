<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\supply_goods\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\open_api\ApiData;
use app\models\Goods;
use app\models\GoodsCatRelation;
use app\models\GoodsCats;
use app\models\GoodsWarehouse;
use app\models\MallGoods;
use app\plugins\supply_goods\models\SupplyGoodsItem;
use yii\helpers\Json;

/**
 * @property MallGoods $mallGoods;
 */
class GoodsSaveForm extends \app\forms\mall\goods\GoodsEditForm
{
    public $price;
    public $original_price;
    public $cost_price;

    public $supply_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['supply_id'], 'integer'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'price' => '零售价',
            'cost_price' => '拿货价',
            'supply_id' => '货源商品id',
        ]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if (count($this->pic_url) <= 0) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '商品有误'
            ];
        }
        if ($this->type == 'ecard') {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '商品不支持'
            ];
        }
        if (!$this->id) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '数据异常'
            ];
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->handleData();
            $this->attrGroupNameValidator();
            $goods = GoodsWarehouse::find()->alias("gw")
                ->innerJoin(['g' => Goods::tableName()], 'g.goods_warehouse_id = gw.id')
                ->where([
                    'gw.supply_id' => $this->supply_id,
                    'gw.is_delete' => 0,
                    'g.is_delete' => 0,
                ])
                ->select("g.id")
                ->asArray()->one();
            if(!empty($goods['id'])){
                $this->id = $goods['id'];
                $this->update();
            } else {
                $this->id = 0;
                $this->add();
            }

            $this->setAttr();
            $this->setGoodsCat();
            $this->setGoodsService();
            $this->setCoupon();
            $this->setListener();
            $this->setGoodsStatusEvent();
            $this->setGoodsItem();

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ]
            ];
        }
    }

    /**
     * @param null|GoodsWarehouse $goodsWarehouse
     * @return GoodsWarehouse|null
     * @throws \Exception
     * 编辑商品库
     */
    protected function editGoodsWarehouse($goodsWarehouse = null)
    {
        if (!$goodsWarehouse) {
            $goodsWarehouse = new GoodsWarehouse();
            $goodsWarehouse->mall_id = \Yii::$app->mall->id;
            $goodsWarehouse->is_delete = 0;
            $goodsWarehouse->type = $this->type;
            $goodsWarehouse->ecard_id = $this->plugin_data['ecard']['ecard_id'] ?? 0;
            if ($goodsWarehouse->type == 'ecard' && $goodsWarehouse->ecard_id == 0) {
                throw new \Exception('卡密类商品需要选择卡密');
            }
            $goodsWarehouse->supply_id = $this->supply_id;
        }
        $goodsWarehouse->name = $this->name;
        $goodsWarehouse->subtitle = $this->subtitle;
        $goodsWarehouse->original_price = $this->original_price;
        $goodsWarehouse->cost_price = $this->cost_price;
        $goodsWarehouse->detail = $this->detail;
        $goodsWarehouse->cover_pic = $this->pic_url[0]['pic_url'];
        $goodsWarehouse->pic_url = \Yii::$app->serializer->encode($this->pic_url);
        $goodsWarehouse->video_url = $this->video_url;
        $goodsWarehouse->unit = $this->unit;
        if (!$goodsWarehouse->save()) {
            throw new \Exception('商品保存失败：' . $this->getErrorMsg($goodsWarehouse));
        }
        $this->goodsWarehouse = $goodsWarehouse;
        return $goodsWarehouse;
    }

    /**
     * 商品分类
     */
    protected function setGoodsCat()
    {
        if (!is_array($this->cats) || !is_array($this->mchCats)) {
            throw new \Exception('分类必须为数组');
        }
        $cache = \Yii::$app->cache->get("supply_goods_cats_".$this->supply_id);
        if(!$cache || !$cache = Json::decode($cache)){
            return;
        }
        $cache = array_column($cache, 'label', 'value');
        GoodsCatRelation::updateAll(['is_delete' => 1], ['is_delete' => 0, 'goods_warehouse_id' => $this->goodsWarehouse->id]);

        $cats = $this->cats ?: $this->mchCats;
        foreach ($cats as $key => $value) {
            if(!isset($cache[$value])){
                continue;
            }
            $model = GoodsCats::findOne([
                'name' => $cache[$value],
                'mall_id' => \Yii::$app->mall->id,
            ]);
            if(!$model){
                $model = new GoodsCats();
                $model->mall_id = \Yii::$app->mall->id;
                $model->name = $cache[$value];
            }
            $model->is_delete = 0;
            $model->status = 1;
            $model->parent_id = $temp[$key] ?? $key;
            if(!$model->save()){
                throw new \Exception($this->getErrorMsg($model));
            }
            $temp[$key] = $model->id;
            $model = GoodsCatRelation::findOne([
                'goods_warehouse_id' => $this->goodsWarehouse->id,
                'cat_id' => $model->id,
            ]);
            if(!$model){
                $model = new GoodsCatRelation();
                $model->cat_id = $temp[$key];
                $model->goods_warehouse_id = $this->goodsWarehouse->id;
            }
            $model->is_delete = 0;
            if(!$model->save()){
                throw new \Exception($this->getErrorMsg($model));
            }
        }
    }

    protected function setGoodsItem(){
        $supplyGoods = SupplyGoodsItem::findOne([
            'goods_warehouse_id' => $this->goodsWarehouse->id,
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if(!$supplyGoods){
            $supplyGoods = new SupplyGoodsItem();
            $supplyGoods->mall_id = \Yii::$app->mall->id;
            $supplyGoods->goods_warehouse_id = $this->goodsWarehouse->id;
        }
        $attr = [];
        foreach ($this->goods->attr as $item){
            $attr[$item->goods_id] = $item->price;
        }
        $supplyGoods->attr = Json::encode($attr);
        $supplyGoods->supply_id = $this->supply_id;
        $supplyGoods->goods_price = $this->cost_price;      // 拿货价
        $supplyGoods->retail_price = $this->price; // 零售价
        if(!$supplyGoods->save()){
            throw new \Exception($this->getErrorMsg($supplyGoods));
        }
    }

    protected function handleData(){
        $this->supply_id = $this->id;

        $apiData = new ApiData();
        if($this->detail){
            $this->detail = $apiData->handleDesc($this->detail);
        }
        $this->video_url = ''; // 视频暂时为空，后面看看转为本地
        foreach ($this->pic_url as $k => $item){
            $this->pic_url[$k]['pic_url'] = $apiData->handleImg($item['pic_url']);
        }
        if($this->attrGroups) {
            $attrPicList = array_column($this->attrGroups[0]['attr_list'], 'pic_url', 'attr_id');
            foreach ($attrPicList as $k => $item){
                $attrPicList[$k] = $apiData->handleImg($item);
            }
        }
        foreach ($this->attr as $k => $item){
            if($item['pic_url']) {
                $this->attr[$k]['pic_url'] = $apiData->handleImg($item['pic_url']);
            }
        }
        if($this->app_share_pic){
            $this->app_share_pic = $apiData->handleImg($this->app_share_pic);
        }
        $this->guarantee_pic = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/statics/img/mall/goods/guarantee/goods-pic.png";
        $this->shareLevelList = [];
        $this->cards = $this->coupons = [];
        $this->mch_id = 0;
    }
}
