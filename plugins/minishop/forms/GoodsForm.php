<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/11
 * Time: 2:37 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\models\Goods;
use app\models\RefundAddress;
use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\models\MinishopBrand;
use app\plugins\minishop\models\MinishopGoods;
use app\plugins\wxapp\Plugin;
use yii\helpers\Json;

class GoodsForm extends Model
{
    public $goods_id;
    public $third_cat_id;
    public $brand_id;
    public $qualification_pics;

    public function rules()
    {
        return [
            [['goods_id', 'third_cat_id', 'brand_id'], 'integer'],
            [['brand_id'], 'default', 'value' => 2100000000],
            [['qualification_pics'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'goods_id' => '商品',
            'third_cat_id' => '分类',
            'brand_id' => '品牌',
            'qualification_pics' => '资质图片'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $goods = Goods::findOne([
            'mall_id' => \Yii::$app->mall->id, 'id' => $this->goods_id, 'is_delete' => 0
        ]);
        if (!$goods) {
            return $this->fail([
                'msg' => '所选商品不存在'
            ]);
        }
        try {
            $catList = CommonRegister::getInstance()->getCat();
            $newCatList = array_column($catList['third_cat_list'], null, 'third_cat_id');
            if (!isset($newCatList[$this->third_cat_id])) {
                throw new \Exception('所选分类不存在0x1');
            }
            $thirdCat = $newCatList[$this->third_cat_id]['third_cat_name'];
            if (!$thirdCat) {
                throw new \Exception('所选分类不存在0x2');
            }
            if ($this->brand_id != 2100000000) {
                $minishopBrand = MinishopBrand::findOne([
                    'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'brand_id' => $this->brand_id
                ]);
                if (!$minishopBrand) {
                    throw new \Exception('所选的品牌不存在');
                }
            }
            /* @var Plugin $plugin */
            $plugin = \Yii::$app->plugin->getPlugin('wxapp');
            $shopService = $plugin->getShopService();
            $refundAddress = RefundAddress::findOne([
                'mall_id' => \Yii::$app->mall->id,
                "mch_id" => \Yii::$app->user->identity->mch_id,
                "is_delete" => 0
            ]);
            if($refundAddress) {
                $refundAddress->address = (array)\Yii::$app->serializer->decode($refundAddress->address);
                $address_info = [
                    'receiver_name' => $refundAddress->name,
                    'detailed_address' => $refundAddress->address_detail,
                    'tel_number' => $refundAddress->mobile,
                    'country' => '中国',
                    'province' => $refundAddress->address[0],
                    'city' => $refundAddress->address[1],
                    'town' => $refundAddress->address[2],
                ];
            }else{
                $address_info = [
                    'receiver_name' => 'xx',
                    'detailed_address' => 'xx',
                    'tel_number' => '13888888888',
                    'country' => '中国',
                    'province' => 'xx',
                    'city' => 'xx',
                    'town' => 'xx',
                ]; // 要给默认值，否则报错 @czs
            }
            $shopService->register->updateInfo([
                'service_agent_type' => [0],
                'default_receiving_address' => $address_info
            ]);

            $attrList = $goods->resetAttr();
            $skus = [];
            $stock = 0;
            $price = null;
            $remotePic = [];

            // @czs
            if (!$this->qualification_pics) {
                $this->qualification_pics = [];
            }else{
                foreach ($this->qualification_pics as $key => $pic){
                    if(!isset($remotePic[$pic])) {
                        $res = $shopService->register->imgUpload($pic);
                        $remotePic[$pic] = $res['img_info']['temp_img_url'];
                    }
                    $this->qualification_pics[$key] = $remotePic[$pic];
                }
            }
            $picUrl = array_column(Json::decode($goods->picUrl, true), 'pic_url');
            foreach ($picUrl as $key => $pic){
                if(!isset($remotePic[$pic])) {
                    $res = $shopService->register->imgUpload($pic);
                    $remotePic[$pic] = $res['img_info']['temp_img_url'];
                }
                $picUrl[$key] = $remotePic[$pic];
            }

            foreach ($goods->attr as $attr) {
                if ($price === null) {
                    $price = $attr->price;
                } else {
                    $price = min($attr->price, $price);
                }
                $skuAttrs = [];
                foreach ($attrList[$attr->sign_id] as $item) {
                    $skuAttrs[] = [
                        'attr_key' => $item['attr_group_name'],
                        'attr_value' => $item['attr_name'],
                    ];
                }

                // @czs
                $attrPic = $attr->pic_url ? $attr->pic_url : $goods->coverPic;
                if(!isset($remotePic[$attrPic])) {
                    $res = $shopService->register->imgUpload($attrPic);
                    $remotePic[$attrPic] = $res['img_info']['temp_img_url'];
                }
                $attrPic = $remotePic[$attrPic];

                $skus[] = [
                    'out_product_id' => $goods->id,
                    'out_sku_id' => $attr->id,
                    'thumb_img' => $attrPic,
                    'sale_price' => $attr->price * 100,
                    'market_price' => $goods->costPrice * 100,
                    'stock_num' => $attr->stock,
                    'barcode' => $attr->bar_code,
                    'sku_code' => $attr->no,
                    'sku_attrs' => $skuAttrs,
                    'weight' => $attr->weight
                ];
                $stock += $attr->stock;
            }
            $args = [
                'out_product_id' => $goods->id,
                'title' => $goods->name,
                'path' => $goods->pageUrl,
                'head_img' => $picUrl,
                'qualification_pics' => $this->qualification_pics,
                'desc_info' => [
                    'desc' => $goods->detail,
                    'imgs' => [],
                ],
                'third_cat_id' => $this->third_cat_id,
                'brand_id' => $this->brand_id,
                'skus' => $skus,
                'attr_group' => Json::decode($goods->attr_groups, true)
            ];
            $model = MinishopGoods::findOne([
                'mall_id' => \Yii::$app->mall->id, 'goods_id' => $goods->id, 'is_delete' => 0
            ]);
            if (!$model) {
                $model = new MinishopGoods();
                $model->mall_id = \Yii::$app->mall->id;
                $model->goods_id = $goods->id;
                $model->status = 0;
                $model->brand = '无品牌';
                $res = $shopService->goods->add($args);
                $model->product_id = $res['data']['product_id'];
            } else {
                $args = array_merge([
                    'product_id' => $model->product_id
                ], $args);
                $res = $shopService->goods->update($args);
            }
            $model->product_info = Json::encode($res['data'], JSON_UNESCAPED_UNICODE);
            $model->is_delete = 0;
            $model->third_cat = $thirdCat;
            $model->apply_status = 1;
            $model->title = $goods->name;
            $model->price = $price;
            $model->stock = $stock;
            unset($args['desc_info']);
            $model->goods_info = Json::encode($args, JSON_UNESCAPED_UNICODE);
            $model->desc = $goods->detail;
            $model->audit_info = Json::encode([], JSON_UNESCAPED_UNICODE);
            $model->qualification_pics = Json::encode($this->qualification_pics, JSON_UNESCAPED_UNICODE);
            if (!$model->save()) {
                throw new \Exception($this->getErrorMsg($model));
            }
            return $this->success([
                'msg' => '提交成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}