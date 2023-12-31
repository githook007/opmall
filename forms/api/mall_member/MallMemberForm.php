<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\api\mall_member;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonMallMember;
use app\forms\common\config\UserCenterConfig;
use app\models\Goods;
use app\models\GoodsCatRelation;
use app\models\GoodsCats;
use app\models\GoodsMemberPrice;
use app\models\Mall;
use app\models\MallMembers;
use app\models\Model;
use app\models\UserIdentity;

class MallMemberForm extends Model
{
    public $id;
    public $limit;
    public $cats_id;

    public function rules()
    {
        return [
            [['id', 'cats_id', 'limit'], 'integer'],
            [['limit'], 'default', 'value' => 10]
        ];
    }

    public function getIndex()
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = \Yii::$app->user->identity->identity;

        $mallMember = MallMembers::find()->where([
            'level' => $userIdentity->member_level,
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'status' => 1
        ])
            ->with('rights')
            ->asArray()->one();

        $nextMallMember = CommonMallMember::getList($userIdentity->member_level, 2);
        $memberCoupons = CommonMallMember::getMallMemberCoupons();

        $common = new CommonMallMember();
        $nextConsumeUpgradeMember = $common->getNextConsumeUpgradeMember();

        $userCenterConfig = UserCenterConfig::getInstance();
        $userCenter = $userCenterConfig->memberData();

        $commonMallMember = new CommonMallMember();
        $orderMoneyCount = $commonMallMember->getOrderMoneyCount();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'consumption_money' => $orderMoneyCount,
                'next_consume_upgrade_member' => $nextConsumeUpgradeMember,
                'user_info' => [
                    'balance' => \Yii::$app->user->identity->userInfo->balance
                ],
                'mall_member' => $mallMember,
                'next_mall_member' => $nextMallMember['list'],
                'member_goods' => [],
                'member_coupons' => $memberCoupons['list'],
                'member_pic_url' => $userCenter['member_pic_url'],
                'member_bg_pic_url' => $userCenter['member_bg_pic_url'],
            ]
        ];
    }

    public function getList()
    {
        $res = CommonMallMember::getList();

        /** @var UserIdentity $userIdentity */
        $userIdentity = UserIdentity::find()->where([
            'user_id' => \Yii::$app->user->id
        ])->select('member_level')->one();

        $price = 0;
        $mall = new Mall();
        $setting  = $mall->getMallSetting();
        foreach ($res['list'] as $key => $item) {
            if($setting['setting']['member_grade']){  // 是否开启会员等级叠加  <@jayi>
                // 会员升级价格累计叠加 并去除当前自身的会员价格
                if ($item['is_purchase'] == 1 && $item['level'] > $userIdentity->member_level) {
                    $price += $item['price'];
                    $res['list'][$key]['price'] = price_format($price);
                }
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $res['list'],
                'pagination' => $res['pagination']
            ]
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $detail = CommonMallMember::getDetail($this->id);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $detail
            ]
        ];
    }

    public function getMemberCoupons()
    {
        $res = CommonMallMember::getMallMemberCoupons();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $res['list'],
                'pagination' => $res['pagination']
            ]
        ];
    }

    public function getMemberGoods()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $res = CommonMallMember::getMallMemberGoods($this->cats_id);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $res['list'],
                'pagination' => $res['pagination']
            ]
        ];
    }

    public function getMemberGoodsCats()
    {
        /** @var UserIdentity $user */
        $user = \Yii::$app->user->identity->identity;

        $goodsIds = GoodsMemberPrice::find()->where([
            'level' => $user->member_level,
            'is_delete' => 0
        ])->select('goods_id');
        $goodsWarehouseIds = Goods::find()->where([
            'id' => $goodsIds,
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id,
            'status' => 1,
            'sign' => '',
            'mch_id' => 0,
        ])->select('goods_warehouse_id');
        $catIds = GoodsCatRelation::find()->where([
            'goods_warehouse_id' => $goodsWarehouseIds,
            'is_delete' => 0,
        ])->select('cat_id');

        $list = GoodsCats::find()->alias('gc')->where([
            'gc.mall_id' => \Yii::$app->mall->id,
            'gc.is_delete' => 0,
            'gc.status' => 1,
            'gc.id' => $catIds
        ])->orderBy('gc.sort')
            ->page($pagination)
            ->all();


        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }
}
