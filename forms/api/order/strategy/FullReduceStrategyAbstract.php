<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/7/25
 * Time: 10:50
 */

namespace app\forms\api\order\strategy;

use app\models\FullReduceActivity;

/**
 * 满减算法接口
 * Interface FullReduceStrategyAbstract
 * @package app\forms\api\order\strategy
 */
interface FullReduceStrategyAbstract
{
    /**
     * @param FullReduceActivity $activity
     * @param $mchItem
     * @param $totalGoodsOriginalPrice
     * @param $totalGoodsPrice
     * @return mixed
     */
    public function discount(FullReduceActivity $activity, $mchItem, $totalGoodsOriginalPrice, $totalGoodsPrice);

    /**
     * @param FullReduceActivity $activity
     * @param $mchItem
     * @param $totalGoodsOriginalPrice
     * @param $totalGoodsPrice
     * @return mixed
     */
    public function nextDiscount(FullReduceActivity $activity, $mchItem, $totalGoodsOriginalPrice, $totalGoodsPrice);
}
