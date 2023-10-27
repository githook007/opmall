<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/21
 * Time: 13:56
 */

namespace app\controllers\behaviors;


use app\bootstrap\response\ApiCode;
use yii\base\ActionFilter;

class LoginFilter extends ActionFilter
{
    public $loginUrl;
    public $safeActions;
    public $onlyActions;
    public $safeRoutes;

    public function beforeAction($action)
    {
        if (is_array($this->safeActions) && in_array($action->id, $this->safeActions)) {
            return parent::beforeAction($action);
        }
        if (is_array($this->safeRoutes) && in_array(\Yii::$app->requestedRoute, $this->safeRoutes)) {
            return parent::beforeAction($action);
        }
        if (is_array($this->onlyActions) && !in_array($action->id, $this->onlyActions)) {
            return parent::beforeAction($action);
        }
        if (!\Yii::$app->user->isGuest) {
            return parent::beforeAction($action);
        }
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请先登录。',
            ];
        } else {
            if (!$this->loginUrl) {
                $url = 'admin/passport/login';
                // todo session失效点击公共管理页面会跳转独立版登录页，此处临时处理，建议使用\Yii::$app->getBranch()->logoutUrl()
                if (is_we7()) {
                    $url = 'mall/we7-entry/logout';
                }
                $this->loginUrl = \Yii::$app->urlManager->createAbsoluteUrl([$url]);
            }
            \Yii::$app->response->redirect($this->loginUrl);
        }
        return false;
    }
}
