<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/13
 * Time: 11:39
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\card;


use app\models\GoodsCardRelation;
use app\models\Model;
use app\models\OrderDetail;
use yii\db\Exception;

class CommonSend extends Model
{
    public $mall_id;
    public $user_id;
    public $order_id;

    const hour = 6; //hour

    /**
     * @return array
     * @throws Exception
     */
    public function save()
    {
        $goodsList = OrderDetail::find()
            ->with('goodsCard.goodsCards')
            ->where([
                'is_delete' => 0,
                'order_id' => $this->order_id
            ])->all();

        if (!$goodsList) {
            throw new Exception('商品不存在，无效的order_id');
        }
        $commonCard = new CommonCard();
        $cardList = [];
        /** @var OrderDetail $item */
        foreach ($goodsList as $item) {
            $goodsCardList = $item->goodsCard;
            if (empty($goodsCardList)) {
                continue;
            }
            /** @var GoodsCardRelation $card */
            foreach ($goodsCardList as $card) {
                if ($card->goodsCards->is_delete !== 0) {
                    continue;
                }
                $count = 0;
                while ($count < bcmul($item->num, $card->num)) {
                    $value = $card->goodsCards;
                    $commonCard->user_id = $this->user_id;
                    $userCard = $commonCard->receive($value, $this->order_id, $item->id);
                    if (!$userCard) {
                        break;
                    }
                    $cardList[] = $userCard;
                    $count++;
                }
            }
        }
        return $cardList;
    }
}
