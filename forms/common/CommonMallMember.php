<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common;


use app\forms\common\coupon\CommonCoupon;
use app\models\Coupon;
use app\models\CouponMemberRelation;
use app\models\Goods;
use app\models\GoodsAttr;
use app\models\GoodsCatRelation;
use app\models\GoodsMemberPrice;
use app\models\MallMembers;
use app\models\Order;
use app\models\OrderRefund;
use app\models\UserCoupon;
use app\models\UserIdentity;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class CommonMallMember
{
    public static $allMember;
    /**
     *  获取所有可用的会员列表
     */
    public static function getAllMember()
    {
        if (self::$allMember) {
            return self::$allMember;
        }
        $all = MallMembers::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'status' => 1,
        ])->orderBy('level')->all();
        self::$allMember = $all;
        return $all;
    }

    /**
     * 商城会员列表(分页)
     * @param int $level
     * @param int $limit
     * @return array
     */
    public static function getList($level = 0, $limit = 20)
    {
        $list = MallMembers::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'status' => 1,
        ])
            ->with('rights')
            ->andWhere(['>', 'level', $level])
            ->orderBy('level')
            ->page($pagination, $limit)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }


    /**
     * 会员详情
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getDetail($id)
    {
        $detail = MallMembers::find()->where([
            'id' => $id
        ])
            ->with(['rights'])
            ->asArray()->one();

        $detail['level'] = (int)$detail['level'];
        $detail['condition_type'] = (int)$detail['condition_type'];

        return $detail;
    }

    /**
     * 获取会员专属商品
     * @param int $catsId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMallMemberGoods($catsId = 0)
    {
        $user = CommonUser::getUserIdentity('member_level');
        $goodsIds = GoodsMemberPrice::find()->where([
            'level' => $user->member_level,
            'is_delete' => 0
        ])->select('goods_id');

        $query = Goods::find()->alias('g')->where([
            'g.mall_id' => \Yii::$app->mall->id,
            'g.is_delete' => 0,
            'g.mch_id' => 0,
            'g.sign' => '',
            'g.status' => 1,
            'g.id' => $goodsIds
        ]);

        if ($catsId) {
            $goodsWarehouseIds = GoodsCatRelation::find()->where([
                'cat_id' => $catsId,
                'is_delete' => 0
            ])->select('goods_warehouse_id');
            $query->andWhere(['g.goods_warehouse_id' => $goodsWarehouseIds]);
        }

        /** @var Goods[] $list */
        $list = $query->with(['goodsWarehouse'])->page($pagination, 10)->all();
        $newList = [];
        foreach ($list as $item) {
            $newItem = ArrayHelper::toArray($item);
            $newItem['name'] = $item->getName();
            $newItem['cover_pic'] = $item->getCoverPic();
            $newItem['price'] = $item->getPrice();
            $newItem['original_price'] = $item->getOriginalPrice();
            $newList[] = $newItem;
        }

        return [
            'list' => $newList,
            'pagination' => $pagination
        ];
    }

    /**
     * 获取会员专属优惠券
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getMallMemberCoupons()
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = \Yii::$app->user->identity->identity;

        $memberCouponQuery = CouponMemberRelation::find()->where([
            'member_level' => $userIdentity->member_level, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id
        ])->select('coupon_id');

        $common = new CommonCoupon();
        $userCouponMemberTable = $common->getTableTemp(\Yii::$app->mall->id, \Yii::$app->user->id);
        $userCouponMemberQuery = (new Query())->from(['a' => $userCouponMemberTable])->select('a.user_coupon_id');

        $userCouponQuery = UserCoupon::find()->alias('uc')->where([
            'uc.is_delete' => 0, 'uc.mall_id' => \Yii::$app->mall->id, 'uc.user_id' => \Yii::$app->user->id
        ])->andWhere(['id' => $userCouponMemberQuery])->groupBy('uc.coupon_id')
            ->select('uc.coupon_id, count(1) count');

//        $userCouponCountQuery = (new Query())->from(['uc' => $userCouponQuery])->where('uc.coupon_id=c.id')
//            ->select('count(1)');

        $list = Coupon::find()->alias('c')->where([
            'c.mall_id' => \Yii::$app->mall->id,
            'c.is_delete' => 0, 'is_member' => 1
        ])->andWhere(['c.id' => $memberCouponQuery])
            ->leftJoin(['ucc' => $userCouponQuery], 'ucc.coupon_id=c.id')
            ->select(['c.*'])
            ->andWhere([
                'or',
                [
                    'and',
                    ['c.expire_type' => 2],
                    ['>', 'c.end_time', date('Y-m-d H:i:s')]
                ],
                ['c.expire_type' => 1]
            ])
            ->addSelect('CASE WHEN `c`.`can_receive_count` = \'-1\'
            || ISNULL(`ucc`.`count`) && `c`.`can_receive_count` > 0 
            || `c`.`can_receive_count` > `ucc`.`count` THEN 0 ELSE 1 END as user_count')
            ->asArray()
            ->page($pagination)
            ->orderBy(['user_count' => SORT_ASC, 'c.sort' => SORT_ASC, 'c.created_at' => SORT_DESC])
            ->all();

        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }

    /**
     * @param $params
     * @param null $level
     * @return \app\models\GoodsMemberPrice|null
     * @throws Exception
     * 获取指定规格指定会员等级的会员价
     */
    public static function getGoodsAttrMemberPrice($params, $level = null)
    {
        if ($params instanceof GoodsAttr) {
            $goodsAttr = $params;
        } else if (is_numeric($params)) {
            $goodsAttr = GoodsAttr::findOne($params);
        } else {
            throw new Exception('错误的参数,$param必须是\app\models\GoodsAttr的对象或对象ID');
        }
        $goodsMemberPrice = null;
        if ($goodsAttr->memberPrice) {
            foreach ($goodsAttr->memberPrice as $item) {
                if ($item->level == $level) {
                    $goodsMemberPrice = $item;
                }
            }
        }
        return $goodsMemberPrice;
    }

    public static $mallMember;

    /**
     * @param $level
     * @return array|\yii\db\ActiveRecord|null|MallMembers
     */
    public static function getMemberOne($level)
    {
        if (isset(self::$mallMember[$level])) {
            return self::$mallMember[$level];
        }
        $result = MallMembers::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'status' => 1,
            'level' => $level
        ])->one();
        self::$mallMember[$level] = $result;

        return $result;
    }

    /**
     * 获取
     */
    public function getNextConsumeUpgradeMember()
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = \Yii::$app->user->identity->identity;
        $result = MallMembers::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'status' => 1,
            'auto_update' => 1
        ])
            ->andWhere(['>', 'level', $userIdentity->member_level])
            ->one();

        return $result;
    }

    public function getOrderMoneyCount($mallId = 0, $userId = 0)
    {
        // 订单总额
        $orderMoneyCount = Order::find()->alias("o")
            ->leftJoin(['ors' => OrderRefund::tableName()], 'ors.order_id = o.id AND ors.is_delete = 0 AND ors.type IN (1, 3) AND ors.is_refund IN (1, 2)')
            ->where([
                'o.mall_id' => $mallId ?: \Yii::$app->mall->id,
                'o.user_id' => $userId ?: \Yii::$app->user->id,
                'o.is_sale' => 1,
                'o.is_delete' => 0,
                'o.is_pay' => 1,
                'o.cancel_status' => 0,
                'o.status' => 1
            ])->sum("
                    `o`.total_pay_price - (
                        CASE
                        WHEN `ors`.`is_refund` = 1 THEN
                            `ors`.reality_refund_price
                        ELSE
                            0
                        END
                    ) - (
                        CASE
                        WHEN `ors`.`is_refund` = 2 THEN
                            `ors`.refund_price
                        ELSE
                            0
                        END
                    )
                ");
        return price_format($orderMoneyCount);
    }
}
