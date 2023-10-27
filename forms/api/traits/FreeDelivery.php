<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/7/6
 * Time: 18:22
 */

namespace app\forms\api\traits;

use app\models\Address;
use app\models\FreeDeliveryRules;
use app\models\PostageRules;

trait FreeDelivery
{
    private $xFreeDeliveryRules;
    private $xPostageRules;

    /**
     * 获取指定的包邮规则，若不存在则取默认包邮规则
     * @param $mall_id
     * @param $mch_id
     * @param $shipping_id
     * @return mixed
     */
    private function getFreeDeliveryRules($mall_id, $mch_id, $shipping_id)
    {
        if (!$this->xFreeDeliveryRules) {
            $this->xFreeDeliveryRules = [];
        }
        $key = $shipping_id . $mch_id;
        if (!empty($this->xFreeDeliveryRules[$key])) {
            return $this->xFreeDeliveryRules[$key];
        }
        /** @var FreeDeliveryRules $rules */
        $rules = FreeDeliveryRules::find()
            ->where([
                        'id' => $shipping_id,
                        'mall_id' => $mall_id,
                        'is_delete' => 0,
                        'mch_id' => $mch_id,
                    ])->limit(1)->one();
        if (empty($rules)) {
            $rules = FreeDeliveryRules::find()
                ->where([
                            'mall_id' => $mall_id,
                            'is_delete' => 0,
                            'mch_id' => $mch_id,
                            'status' => 1
                        ])->limit(1)->one();
        }
        $this->xFreeDeliveryRules[$key] = $rules;
        return $this->xFreeDeliveryRules[$key];
    }

    /**
     * 获取包邮规则对应地区的包邮金额或件数
     * @param FreeDeliveryRules $deliveryRules
     * @param Address $address
     * @return int|mixed
     */
    private function getCondition(FreeDeliveryRules $deliveryRules, $address)
    {
        $districts = $deliveryRules->decodeDetail();
        $inDistrict = false;
        $condition = -1;
        foreach ($districts as $district) {
            foreach ($district['list'] as $item) {
                if ($item['id'] == $address->province_id) {
                    $inDistrict = true;
                    $condition = $district['condition'];
                    break;
                } elseif ($item['id'] == $address->city_id) {
                    $inDistrict = true;
                    $condition = $district['condition'];
                    break;
                } elseif ($item['id'] == $address->district_id) {
                    $inDistrict = true;
                    $condition = $district['condition'];
                    break;
                }
            }
            if ($inDistrict) {
                break;
            }
        }
        return $condition;
    }

    /**
     * 获取包邮文字信息
     * @param $mall_id
     * @param $mch_id
     * @param $shipping_id
     * @return string
     * @throws \Exception
     */
    private function getShippingText($mall_id, $mch_id, $shipping_id)
    {
        /**@var FreeDeliveryRules $freeDelivery**/
        $freeDelivery = $this->getFreeDeliveryRules(
            $mall_id,
            $mch_id,
            $shipping_id
        );
        if ($freeDelivery) {
            $shipping = '';
            $districts = $freeDelivery->decodeDetail();
            switch ($freeDelivery->type) {
                case 1:
                    foreach ($districts as $i) {
                        $shipping .= sprintf('订单满%s元包邮', $i['condition']);
                        $shipping .= '(';
                        $shipping .= implode('、', array_column((array)$i['list'], 'name'));
                        $shipping .= ')，';
                    }
                    break;
                case 2:
                    foreach ($districts as $i) {
                        $shipping .= sprintf('订单满%s件包邮', $i['condition']);
                        $shipping .= '(';
                        $shipping .= implode('、', array_column((array)$i['list'], 'name'));
                        $shipping .= ')，';
                    }
                    break;
                case 3:
                    foreach ($districts as $i) {
                        $shipping .= sprintf('单品满%s元包邮', $i['condition']);
                        $shipping .= '(';
                        $shipping .= implode('、', array_column((array)$i['list'], 'name'));
                        $shipping .= ')，';
                        if ($i['condition'] < $this->getPriceMin()) {
                            $this->isExpress = true;
                        }
                    }
                    break;
                case 4:
                    foreach ($districts as $i) {
                        $shipping .= sprintf('单品满%s件包邮', $i['condition']);
                        $shipping .= '(';
                        $shipping .= implode('、', array_column((array)$i['list'], 'name'));
                        $shipping .= ')，';
                        if ($i['condition'] == 1) {
                            $this->isExpress = true;
                        }
                    }
                    break;
                default:
                    throw new \Exception('未知的包邮类型');
            }
            return trim($shipping, '，');
        }

        return '';
    }

    private function getPostageRules($freight_id, $mch_id){
        if (!$this->xPostageRules) {
            $this->xPostageRules = [];
        }
        $key = $freight_id . $mch_id;
        if (!empty($this->xPostageRules[$key])) {
            return $this->xPostageRules[$key];
        }
        if ($freight_id && $freight_id != -1) {
            $postageRule = PostageRules::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $freight_id,
                'is_delete' => 0,
                'mch_id' => $mch_id,
            ]);
        }
        if (empty($postageRule)) {
            $postageRule = PostageRules::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'status' => 1,
                'is_delete' => 0,
                'mch_id' => $mch_id,
            ]);
        }
        $this->xPostageRules[$key] = $postageRule;
        return $postageRule;
    }
}
