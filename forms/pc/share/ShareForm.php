<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\share;

use app\bootstrap\response\ApiCode;
use app\forms\api\poster\PosterForm;
use app\forms\common\share\CommonShareLevel;
use app\models\UserIdentity;
use app\forms\common\share\CommonShareTeam;
use app\models\ShareSetting;

class ShareForm extends \app\forms\api\share\ShareForm
{
    public function search()
    {
        $identity = UserIdentity::findOne([
            'is_delete' => 0,
            'is_distributor' => 1,
            'user_id' => \Yii::$app->user->id,
        ]);
        if (!$identity) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '权限不足'
            ];
        }

        //获取我的团队
        $team = new CommonShareTeam();
        $team->mall = \Yii::$app->mall;
        $setting = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::LEVEL, 0);
        $first_count = $setting > 0 ? count($team->info(\Yii::$app->user->id, 1)) : 0;
        $second_count = $setting > 1 ? count($team->info(\Yii::$app->user->id, 2)) : 0;
        $third_count = $setting > 2 ? count($team->info(\Yii::$app->user->id, 3)) : 0;
        $list['team_count'] = $first_count + $second_count + $third_count; //团队数量

        $order = $this->getOrderCount();
        $list['order_money'] = $order['order_money']; //订单总额
        $list['order_money_un'] = $order['order_money_un']; //未结算佣金
        $shareLevel = CommonShareLevel::getInstance()->getShareLevelByLevel(\Yii::$app->user->identity->share->level);
        $list['level_name'] = $shareLevel ? $shareLevel->name : '无';
        $list['level'] = \Yii::$app->user->identity->share->level;

        //获取分销佣金及提现
        $price = $this->getPrice();

        $form = new PosterForm();
        \Yii::$app->setAppPlatform(\Yii::$app->user->identity->userInfo ? \Yii::$app->user->identity->userInfo->platform : "wxapp");
        $res = $form->poster('share');
        $list['qr_code'] = $res['data']['pic_url'];
        $list['url'] = \Yii::$app->request->hostInfo . "/index.html#/homepage/index?p_user_id=".\Yii::$app->user->id;

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => array_merge($list, $price),
        ];
    }
}
