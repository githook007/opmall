<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/1/29
 * Time: 11:14
 * @copyright: ©2021 .hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\pc\user;

use app\models\User;

class UserInfoForm extends \app\forms\api\user\UserInfoForm
{
    public function getInfo()
    {
        $result = $this->userInfo();
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $cacheKey = 'user_register_' . $user->id . '_' . $user->mall_id;
        $couponList = \Yii::$app->cache->get($cacheKey);
        if ($couponList && count($couponList) > 0) {
            $result['register'] = ['coupon_list' => $couponList];
            \Yii::$app->cache->delete($cacheKey);
        }
        if(empty($result['avatar'])){
            $result['avatar'] = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/statics/img/default-avatar.png";
        }

        return [
            'code' => 0,
            'data' => $result,
        ];
    }
}
