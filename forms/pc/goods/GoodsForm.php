<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/11/5
 * Time: 17:07
 * @copyright: ©2021 .hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\pc\goods;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonQrCode;
use app\forms\common\ecard\CommonEcard;
use app\forms\common\goods\CommonGoods;
use app\forms\common\goods\CommonGoodsDetail;
use app\forms\common\goods\CommonGoodsMember;
use app\forms\common\video\Video;
use app\forms\pc\SettingConf;
use app\forms\pc\SettingForm;
use app\models\Goods;
use app\models\Model;
use app\plugins\mch\models\Mch;
use app\plugins\mch\models\MchSetting;

/**
 * @package app\forms\pc\goods
 * @property Goods $goods
 */
class GoodsForm extends Model
{
    private static $instance;
    public $goods;
    public $id;
    public $mch_id = 0;

    public $hasMember = false;
    public $tempGoodsDetail;

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 列表详情
    public function getDetails()
    {
        $isNegotiable = $this->getNegotiable();
        $minPrice = 0;
        foreach ($this->goods->attr as $key => $value) {
            $minPrice = $minPrice == 0 ? $value->price : min($minPrice, $value->price);
        }

        $goodsStock = array_sum(array_column($this->goods->attr, 'stock')) ?? 0;
        $goodsStock = CommonEcard::getCommon()->getEcardStock($goodsStock, $this->goods);
        $data = [
            'id' => $this->goods->id,
            'goods_warehouse_id' => $this->goods->goods_warehouse_id,
            'mch_id' => $this->goods->mch_id,
            'sign' => $this->goods->sign,
            'name' => $this->goods->name,
            'cover_pic' => $this->goods->coverPic,
            'video_url' => Video::getUrl(trim($this->goods->videoUrl)),
            'original_price' => $this->goods->originalPrice,
            'unit' => $this->goods->unit,
            'page_url' => $this->goods->pageUrl,
            'is_negotiable' => $isNegotiable,
            'is_level' => $this->goods->is_level,
            'level_price' => $this->getGoodsMember(),
            'price' => $minPrice,
            'price_content' => $this->getPriceContent($isNegotiable, $minPrice),
            'sales' => $this->getSales(1, $this->goods->unit),
            'goods_stock' => $goodsStock,
            'goods_num' => $goodsStock,
            'type' => $this->goods->goodsWarehouse->type,
        ];
        // 插件
        try {
            if ($this->goods->sign) {
                $plugin = \Yii::$app->plugin->getPlugin($this->goods->sign);
                $config = $plugin->getOrderConfig();
                if ($config['is_member_price'] == 0) {
                    $data['is_level'] = 0;
                }
            }
        }catch (\Exception $exception) {}
        return $data;
    }

    // 商品详情页
    public function getDetail()
    {
        try {
            if(empty($this->id)){
                throw new \Exception('商品ID不存在');
            }

            $form = new CommonGoodsDetail();
            $form->user = \Yii::$app->user->identity;
            $form->mall = \Yii::$app->mall;
            $form->mch_id = $this->mch_id;
            $goods = $form->getGoods($this->id);
            if (!$goods) {
                throw new \Exception('商品不存在');
            }
            if ($goods->status != 1) {
                throw new \Exception('商品未上架');
            }

            $form->goods = $goods;
            $mallGoods = CommonGoods::getCommon()->getMallGoods($goods->id);
            $form->setMember($mallGoods->is_negotiable == 0);
            $form->setShare($mallGoods->is_negotiable == 0);
            $res = $form->getAll([
                'attr', 'goods_num', 'goods_no', 'goods_weight', 'attr_group', 'services',
                'price_min', 'price_max', 'pic_url', 'share', 'sales', 'favorite', 'goods_marketing',
                'goods_marketing_award'
            ]);
            $res = array_merge($res, [
                'is_sell_well' => $mallGoods->is_sell_well,
                'is_negotiable' => $mallGoods->is_negotiable,
            ]);

            //图片替换
            $temp = [];
            foreach ($res['attr'] as $v) {
                foreach ($v['attr_list'] as $w) {
                    if (!isset($temp[$w['attr_id']])) {
                        $temp[$w['attr_id']] = $v['pic_url'];
                    }
                }
            }

            foreach ($res['attr_groups'] as $k => $v) {
                foreach ($v['attr_list'] as $l => $w) {
                    $res['attr_groups'][$k]['attr_list'][$l]['pic_url'] = $temp[$w['attr_id']] ?: "";
                }
            }

            $query = Goods::find()->with(['goodsWarehouse' => function ($query) {
                $query->where(['type' => "goods", 'is_delete' => 0]);
            }])->where([
                'mall_id' => \Yii::$app->mall->id,
                'status' => 1,
                'is_delete' => 0,
            ]);
            if($res["mch_id"]){
                $mchData = Mch::findOne(["mall_id" => \Yii::$app->mall->id, "id" => $res['mch_id']]);
                $setting = MchSetting::findOne(["mall_id" => \Yii::$app->mall->id, "mch_id" => $res['mch_id']]);
                $store = [
                    "name" => $mchData->realname, "store_name" => $mchData->store->name, "mobile" => $mchData->store->mobile,
                    "address" => $mchData->store->address, "web_service_url" => $setting->web_service_url,
                    "desc" => $mchData->store->description, "logo" => $mchData->store->cover_url
                ];
                $wxAppPage = "plugins/mch/shop/shop";
                $query->andWhere(["mch_id" => $res['mch_id']]);
            }else{
                $form = new SettingForm();
                $mallSetting = \Yii::$app->mall->getMallSetting(['contact_tel', 'quick_map_address', "mall_logo_pic", 'web_service_url']);
                $store = [
                    "name" => \Yii::$app->mall->name, "store_name" => \Yii::$app->mall->name, "mobile" => $mallSetting['contact_tel'],
                    "address" => $mallSetting['quick_map_address'], "web_service_url" => $mallSetting['web_service_url'],
                    "desc" => $form->getData(SettingConf::$basicSetting)[SettingConf::MALL_DESC], "logo" => $mallSetting['mall_logo_pic']
                ];
                $wxAppPage = "pages/index/index";
            }
            try {
                $form = new CommonQrCode();
                $form->appPlatform = \Yii::$app->user->identity->userInfo ? \Yii::$app->user->identity->userInfo->platform : "wxapp";
                $qrRes = $form->getQrCode(['mch_id' => $res["mch_id"]], 150, $wxAppPage);
                $store['store_qr_code'] = $qrRes['file_path'];
            } catch (\Exception $exception) {
                $store['store_qr_code'] = "";
            }
            $store['goods_count'] = $query->count();
            $store["mch_id"] = $res["mch_id"];

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => 'success',
                'data' => [
                    'goods' => $res,
                    'store' => $store
                ]
            ];
        } catch (\Exception $e) {
            \Yii::error($e);
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return int
     * 获取是否价格面议
     */
    protected function getNegotiable()
    {
        $data = 0;
        if ($this->goods->sign == '') {
            $mallGoods = $this->goods->mallGoods;
            $data = $mallGoods->is_negotiable;
        }
        return $data;
    }

    /**
     * @return string
     * 获取会员价
     */
    protected function getGoodsMember()
    {
        return CommonGoodsMember::getCommon()->getGoodsMemberPrice($this->goods);
    }

    /**
     * @param int $isNegotiable
     * @param string $minPrice
     * @return string
     * 获取售价文字版
     */
    protected function getPriceContent($isNegotiable, $minPrice)
    {
        if ($isNegotiable == 1) {
            $priceContent = '价格面议';
        } elseif ($minPrice > 0) {
            $priceContent = '￥' . $minPrice;
        } else {
            $priceContent = '免费';
        }
        return $priceContent;
    }

    /**
     * @param int $isSales
     * @param string $unit
     * @return string
     * 获取销量
     */
    protected function getSales($isSales, $unit = '件')
    {
        $sales = '';
        if ($isSales == 1) {
            $sales = $this->goods->virtual_sales + $this->goods->sales;
            $length = strlen($sales);

            if ($length > 8) { //亿单位
                $sales = substr_replace(substr($sales, 0, -7), '.', -1, 0) . "亿";
            } elseif ($length > 4) { //万单位
                $sales = substr_replace(substr($sales, 0, -3), '.', -1, 0) . "w";
            }
            $sales = sprintf("%s%s", $sales, $unit);
        }
        return $sales;
    }
}
