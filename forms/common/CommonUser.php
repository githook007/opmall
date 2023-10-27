<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common;

use app\forms\common\platform\PlatformConfig;
use app\models\AdminInfo;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;
use app\plugins\mch\models\MchMallSetting;

class CommonUser
{
    /**
     * 搜索用户
     * @param  [type] $keyword [description]
     * @return [type]          [description]
     */
    public static function searchUser($keyword)
    {
        $keyword = trim($keyword);

        $query = User::find()->alias('u')->where([
            'AND',
            ['or', ['LIKE', 'u.nickname', $keyword], ['u.id' => $keyword], ['u.mobile' => $keyword]],
            ['u.mall_id' => \Yii::$app->mall->id],
        ]);
        $list = $query->InnerJoinwith('userInfo')->with(['userPlatform'])
            ->orderBy('nickname')->limit(30)->all();

        $newList = [];
        $platformConfig = new PlatformConfig();
        /** @var User[] $list */
        foreach ($list as $k => $v) {
            $newList[] = [
                'id' => $v->id,
                'nickname' => $v->nickname,
                'avatar' => $v->userInfo->avatar,
                'platform_icon' => $platformConfig->getPlatformIcon($v)
            ];
        }
        return [
            'list' => $newList,
        ];
    }

    public static function getUserInfo($columns = '')
    {
        if($columns) {
            return UserInfo::find()->where([
                'user_id' => \Yii::$app->user->id,
                'is_delete' => 0
            ])->select($columns)->one();
        }else{
            return \Yii::$app->user->identity->userInfo;
        }
    }

    /**
     * @param string $columns
     * @return array|\yii\db\ActiveRecord|null|AdminInfo
     */
    public static function getAdminInfo($columns = '')
    {
        if($columns) {
            $adminInfo = AdminInfo::find()->where([
                'user_id' => \Yii::$app->user->id,
                'is_delete' => 0
            ])->select($columns)->one();
        }else{
            $adminInfo = \Yii::$app->user->identity->adminInfo;
        }
        if ($adminInfo && isset($adminInfo->app_max_count)) {
            /* @var User $user */
            $user = \Yii::$app->user->identity;
            if ($user->identity->is_super_admin == 1) {
                $adminInfo->app_max_count = -1;
            }
        }
        return $adminInfo;
    }

    /**
     * @param string $columns
     * @return array|\yii\db\ActiveRecord|null|UserIdentity
     */
    public static function getUserIdentity($columns = '')
    {
        if($columns) {
            return UserIdentity::find()->where([
                'user_id' => \Yii::$app->user->id,
            ])->select($columns)->one();
        }

        return \Yii::$app->user->identity->identity;
    }

    /**
     * 获取员工的所有权限路由数组
     */
    public static function getUserPermissions()
    {
        $authRole = \Yii::$app->user->identity->role;

        $newPermissions = ['mall/index/index'];
        foreach ($authRole as $item) {
            $newPermissions = array_merge($newPermissions, json_decode($item->permissions->permissions));
        }
        $newPermissions = array_values(array_unique($newPermissions));

        // 插件首页路由比较特殊！需判断是否有插件路由再加上
        foreach ($newPermissions as $item) {
            if (strpos($item, 'plugin/') !== false) {
                $newPermissions[] = 'mall/plugin/index';
                break;
            }
        }

        return $newPermissions;
    }

    /**
     * 多商户权限路由
     * @return array
     */
    public static function getMchPermissions()
    {
        $route = [
            'mall/index/index',
            'mall/mch/setting',
            'mall/mch/manage',
            'mall/sms/setting',
            'mall/material/index',
            'mall/index/mail',
            'mall/postage-rule/index',
            'mall/postage-rule/edit',
            'mall/free-delivery-rules/index',
            'mall/free-delivery-rules/edit',
            'mall/express/index',
            'mall/express/edit',
            'mall/printer/index',
            'mall/printer/edit',
            'mall/printer/setting',
            'mall/territorial-limitation/index',
            'mall/offer-price/index',
            'mall/refund-address/index',
            'mall/refund-address/edit',
            'mall/goods/index',
            'mall/goods/edit',
            'mall/cat/index',
            'mall/cat/edit',
            'mall/service/index',
            'mall/service/edit',
            'mch/goods/taobao-copy',
            'mall/order/index',
            'mall/order/detail',
            'mall/order/offline',
            'mall/order/refund',
            'mall/order/batch-send-model',
            'mall/order-comments/index',
            'mall/order-comments/edit',
            'mall/order-comments/reply',
            'mall/order/batch-send',
            'mch/store/order-message',
            'mall/user/clerk',
            'mall/mch/account-log',
            'mall/mch/cash-log',
            'mall/mch/order-close-log',
            'mall/order-comment-templates/index',
            'mall/order/refund-detail',
            'mall/index/rule',
            'mall/goods/import-goods-log',
            'mall/goods/export-goods-list',
            'mall/order-send-template/index',
            'mall/order-send-template/edit',
            'mall/goods-attr-template/index',
            'mall/goods/import-data',
            'mall/cat/import-cat-log',
            'mall/data-statistics/index',
            'mall/notice/detail',
            'mall/data-statistics/goods_top',
            'mall/data-statistics/users_top',
            'mall/data-statistics/all-num',
            'mall/order-form/list',
            'mall/order-form/setting',
            'mall/mch/share-order',
            'mall/file/index',
            'mall/mch/fast-login-back',
        ];
        $assistant = [
            'mall/assistant/index',
            'mall/assistant/collect',
        ];
        try {
            // 获取商城所属账户的权限
            $accountPermission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
            if (in_array('assistant', $accountPermission)) {
                $route = array_merge($route, $assistant);
            }
            // 获取商户是否有优惠券 // @czs
            $is_coupon = MchMallSetting::find()->where([
                'mall_id' => \Yii::$app->mall->id,
                'mch_id' => \Yii::$app->user->identity->mch_id,
                "is_coupon" => 1
            ])->count();
            if($is_coupon) {
                $route = array_merge($route, [
                    'mall/coupon/index',
                    'mall/coupon/edit',
                    'mall/coupon/delete',
                    'mall/coupon/send',
                    'mall/coupon/use-log',
                    'mall/send-statistics/index',
                    'mall/coupon-auto-send/index',
                    'mall/coupon-auto-send/edit',
                    'mall/coupon-auto-send/delete',
                ]);
            }
            // end
        } catch (\Exception $exception) {
            \Yii::warning($exception);
        }
        return $route;
    }

    public static function whoUser(User $user = null){
        $params = \Yii::$app->request->post();
        /** @var User $user */
        $user = $user ?: \Yii::$app->user->identity;
        if(isset($params['model'])){
            if($params['model'] == 'mch'){
                if($user->mch) {
                    return $user->mch->realname;
                }
            }elseif($params['model'] == 'share'){
                if($user->share && $user->is_delete == 0){
                    return $user->share->name;
                }
            }
        }
        return $user->userInfo->remark_name;
    }
}