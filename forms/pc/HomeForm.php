<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c)
 * author: opmall
 */
namespace app\forms\pc;

use app\forms\common\goods\CommonGoodsList;
use app\forms\pc\goods\GoodsForm;
use app\models\Goods;
use app\models\GoodsCats;
use app\models\pc\Banner;
use app\models\pc\Nav;
use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;

class HomeForm
{
    public function getGlobalData(){
        $data = [];

        // 获取小程序二维码
        $data['qrcode'] = "";
        try {
            $accessToken = \Yii::$app->getWechat()->getAccessToken();
            $api = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$accessToken}";
            $client = new Client();
            $params = json_encode(['path' => 'pages/index/index', 'width' => 256], JSON_UNESCAPED_UNICODE);
            $response = $client->post($api, ['verify' => false, 'body' => $params]);
            $contentTypes = $response->getHeader('Content-Type');
            $body = $response->getBody();
            foreach ($contentTypes as $contentType) {
                if (mb_stripos($contentType, 'image') !== false) {
                    $filePath = \Yii::$app->basePath . '/web/temp/';
                    make_dir($filePath);
                    if (file_put_contents($filePath . '/pc.jpg', $body) !== false) {
                        $data['qrcode'] =  \Yii::$app->request->hostInfo . '/web/temp/pc.jpg';
                    }
                }
            }
        } catch (\Exception $exception) {}

        // 导航栏
        $data['nav_list'] = Nav::find()->where(["mall_id" => \Yii::$app->mall->id, "status" => 1, "is_delete" => 0])
            ->orderBy("sort asc,id desc")
            ->asArray()
            ->all();

        // 配置项
        $form = new SettingForm();
        $setting = $form->getData(SettingConf::$pcHomeData);
        $data['setting'] = $setting;

        // 获取分类
        $data['cat_list'] = $this->getCat();

        return [
            'code' => 0,
            'data' => $data,
            "msg" => "请求成功"
        ];
    }

    public function getHomeData(){
        $data = [];

        // 轮播图
        $data['banner_list'] = Banner::find()->where(["mall_id" => \Yii::$app->mall->id, "is_delete" => 0])
            ->orderBy("sort asc,id desc")
            ->asArray()
            ->all();

        // 广告图
        $form = new SettingForm();
        $data['ad_list'] = $form->getData(SettingConf::$homeAd);
        $data['ad_list']["title"] = $data['ad_list']["ad_title"];
        unset($data['ad_list']["ad_title"]);

        // 为你推荐
        $form = new SettingForm();
        $data['recommend_goods'] = $form->getData(SettingConf::$recommendData);
        $this->handleGoodsList($data['recommend_goods']);

        return [
            'code' => 0,
            'data' => $data,
            "msg" => "请求成功"
        ];
    }

    public function getCat($mch_id = 0){
        $list = GoodsCats::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'parent_id' => 0,
            'is_delete' => 0,
            'mch_id' => $mch_id,
            'status' => 1,
            'is_show' => 1,
        ])->with(['child' => function ($query) use($mch_id) {
            $query->with(['child' => function ($query) use($mch_id) {
                $query->andWhere(['mch_id' => $mch_id, 'status' => 1, 'is_show' => 1])->orderBy(['sort' => SORT_ASC, 'created_at' => SORT_ASC])
                    ->select("id,name,pic_url,big_pic_url,parent_id");
            }])->andWhere(['mch_id' => $mch_id, 'status' => 1, 'is_show' => 1])->orderBy(['sort' => SORT_ASC, 'created_at' => SORT_ASC])
                ->select("id,name,pic_url,big_pic_url,parent_id");
        }])->select("id,name,pic_url,big_pic_url,parent_id")
            ->orderBy(['sort' => SORT_ASC, 'created_at' => SORT_ASC])
            ->asArray()
            ->all();

        $func = function ($data) use (&$func) {
            if (isset($data['child'])) {
                foreach ($data['child'] as $key => $item) {
                    $data['child'][$key] = $func($item);
                }
            }
            return $data;
        };

        foreach ($list as $k => $v) {
            $list[$k] = $func($v);
        }
        return $list;
    }

    public function handleGoodsList(&$data){
        $form = new CommonGoodsList();
        $form->sort = 4; // 销量
        $favoriteList = $this->getPcList($form);
        if(!empty($data['recommend_cat_list'])){
            foreach ($data['recommend_cat_list'] as $k => $item){
                $form->cat_id = $item['id'];
                $form->sort = 4; // 销量
                $data['recommend_cat_list'][$k]['goods_list'] = $this->getPcList($form);
            }
            array_unshift($data['recommend_cat_list'], ["id" => 0, "name" => "精选", "goods_list" => $favoriteList]);
        }else{
            $data['recommend_cat_list'][] = ["id" => 0, "name" => "精选", "goods_list" => $favoriteList];
        }
    }

    /**
     * @param CommonGoodsList $form
     * @return array
     * web前端商品列表
     */
    public function getPcList($form)
    {
        $form->limit = 10;
        $form->status = 1;
        $form->is_sales = 1;
        $form->sign = ['mch', ''];
        $form->is_array = false;
        $list = $form->search();

        $newList = [];
        /* @var Goods[] $list */
        foreach ($list as $item) {//
            GoodsForm::getInstance()->goods = $item;
            $newList[] = GoodsForm::getInstance()->getDetails();
        }
        return $newList;
    }
}
