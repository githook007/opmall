<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2019 hook007
 * author: opmall
 */

namespace app\controllers\admin\behaviors;

use app\forms\common\CommonAuth;
use app\forms\common\CommonUser;
use app\models\User;
use yii\base\ActionFilter;
use Yii;

class PermissionsBehavior extends ActionFilter
{
    /**
     * 安全路由，权限验证时会排除这些路由
     * @var array
     */
    public $safeRoute = [];

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest == false) {

            //路由名称
            $route = Yii::$app->requestedRoute;
            //排除安全路由
            if (in_array($route, $this->safeRoute)) {
                return true;
            }

            // TODO 异步请求不验证
            if (Yii::$app->request->isAjax) {
                return true;
            }

            if(\Yii::$app->getSessionMallId()){
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('mall/index/index'))->send();
            }

            // 多商户
            if (Yii::$app->user->identity->mch_id) {
                $mchAuthRoute = CommonUser::getMchPermissions();
                if (in_array($route, $mchAuthRoute)) {
                    return true;
                }
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('/mall/index/index'))->send();
            }

            // 超级管理员无需验证
            $userIdentity = CommonUser::getUserIdentity();
            if ($userIdentity->is_super_admin == 1) {
                return true;
            }

            // 子账号管理员
            if ($userIdentity->is_admin == 1) {
                $notPermissionRoutes = CommonAuth::getPermissionsRouteList();
                if (in_array($route, $notPermissionRoutes)) {
                    \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('/admin/user/me'))->send();
                }
                return true;
            }

            // 员工账户
            if($userIdentity->is_operator){
                $authRoute = CommonUser::getUserPermissions();
                if (in_array($route, $authRoute)) {
                    return true;
                }
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('/mall/index/index'))->send();
            }

            // 判断登录角色再决定
            $role = $_COOKIE['__login_role'] ?? '';
            if($role == User::LOGIN_CASHIER){ // 收银台的
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('plugin/teller/web/manage/index'))->send();
            }
            if($token = $_COOKIE['__login_token']){
                $user = User::findOne([
                    'access_token' => $token,
                    'is_delete' => 0,
                ]);
                Yii::$app->user->login($user);
            }
        }

        return true;
    }
}
