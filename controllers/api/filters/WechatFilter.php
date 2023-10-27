<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/12/2
 * Time: 2:05 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\controllers\api\filters;


use app\bootstrap\response\ApiCode;
use yii\base\ActionFilter;
use yii\helpers\Json;

class WechatFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (\Yii::$app->appPlatform !== 'wechat') {
            return true;
        }
        try {
            \Yii::warning('--关注公众号判断--');
            $plugin = \Yii::$app->plugin->getPlugin('wechat');
            if (!method_exists($plugin, 'getOther')) {
                return true;
            }
            $config = $plugin->getOther();
            $routerList = [];
            foreach ($config as $value) {
                if ($value['check'] == 1) {
                    $routerList = array_merge($routerList, $value['router']);
                }
            }
            \Yii::warning('--需要关注公众号的路由：' . Json::encode($routerList, JSON_UNESCAPED_UNICODE));
            $router = \Yii::$app->requestedRoute;
            \Yii::warning('--当前的路由：' . $router);
            if (!in_array($router, $routerList)) {
                return true;
            }
            if (!method_exists($plugin, 'updateSubscribe')) {
                return true;
            }
            $res = $plugin->updateSubscribe();
            \Yii::warning($res);
            if ($res['code'] == 0 && $res['data']['subscribe'] == 1) {
                return true;
            }
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_WECHAT_SUBSCRIBE,
                'msg' => '请先关注公众号'
            ];
            return false;
        } catch (\Exception $exception) {
            \Yii::warning($exception);
            return true;
        }
    }
}
