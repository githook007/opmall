<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\mch;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonQrCode;
use app\forms\common\goods\CommonGoodsStatistic;
use app\forms\common\mch\MchSettingForm;
use app\forms\common\mch\SettingForm;
use app\forms\common\order\CommonOrderStatistic;
use app\forms\pc\HomeForm;
use app\plugins\mch\forms\common\CommonCat;
use app\plugins\mch\models\Mch;
use yii\helpers\ArrayHelper;

class MchForm extends \app\plugins\mch\forms\api\MchForm
{
    public function setting()
    {
        try {
            $form = new MchSettingForm();
            $form->isDefaultCashType = true;
            $res = $form->search();

            $form = new CommonCat();
            $list = $form->getAllList();

            if(\Yii::$app->user) {
                $mch = Mch::find()->where([
                    'user_id' => \Yii::$app->user->id,
                    'is_delete' => 0,
                    'mall_id' => \Yii::$app->mall->id,
                ])->one();
                if(!$mch){
                    $mch = ["review_status" => 2];
                }
            }else{
                $mch = [];
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => ["setting" => $res, "category" => $list, "mch" => $mch]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $query = Mch::find()->where([
                'id' => $this->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
                'review_status' => 1
            ]);
            /** @var Mch $detail */
            $detail = $query->with('mchUser', 'store')->one();
            if (!$detail) {
                throw new \Exception('商户不存在');
            }
            $detail->form_data = !$detail->form_data ?: \Yii::$app->serializer->decode($detail->form_data);
            $detail->store->pic_url = !$detail->store->pic_url ?: \Yii::$app->serializer->decode($detail->store->pic_url);
            $newDetail = ArrayHelper::toArray($detail);
            unset($newDetail['account_money']);
            $newDetail['mchUser'] = ArrayHelper::toArray($detail->mchUser);
            $newDetail['store'] = ArrayHelper::toArray($detail->store);

            // 商户商品统计
            $form = new CommonGoodsStatistic();
            $form->mch_id = $this->id;
            $form->sign = "mch";
            $res = $form->getAll(['goods_count']);
            $newDetail['goods_count'] = $res['goods_count'];

            // 商户订单统计
            $form = new CommonOrderStatistic();
            $form->mch_id = $this->id;
            $form->sign = "mch";
            $form->is_user = 1;
            $res = $form->getAll(['order_goods_count']);
            $newDetail['order_goods_count'] = $res['order_goods_count'];

            $form = new SettingForm();
            $form->mch_id = $this->id;
            $setting = $form->search();
            $newDetail['store']['web_service_url'] = urldecode($setting['web_service_url']);
            $newDetail['store']['web_service_pic'] = $setting['web_service_pic'];
            try {
                $form = new CommonQrCode();
                $form->appPlatform = \Yii::$app->user->identity->userInfo ? \Yii::$app->user->identity->userInfo->platform : "wxapp";
                $qrRes = $form->getQrCode(['mch_id' => $this->id], 150, 'plugins/mch/shop/shop');
                $newDetail['store']['store_qr_code'] = $qrRes['file_path'];
            } catch (\Exception $exception) {
                $newDetail['store']['store_qr_code'] = "";
            }

            $latitude2 = $detail->store && $detail->store->latitude ? $detail->store->latitude : 0;
            $longitude2 = $detail->store && $detail->store->longitude ? $detail->store->longitude : 0;
            if ($this->latitude && $this->longitude && $latitude2 && $longitude2) {
                $distance = get_distance($this->latitude, $this->longitude, $latitude2, $longitude2);
            }else {
                $distance = '';
            }
            $newDetail['distance'] = $distance ? round($distance, 2) . 'm' : '';

            $form = new HomeForm();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $newDetail,
                    'cat_list' => $form->getCat($this->id)
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
