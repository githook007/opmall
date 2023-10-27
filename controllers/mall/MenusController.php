<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\mall;


use app\bootstrap\response\ApiCode;
use app\forms\permission\menu\MenusForm;

class MenusController extends MallController
{
    public function actionIndex()
    {
        $route = \Yii::$app->request->post('route');

        $form = new MenusForm();
        $form->currentRoute = $route;
        $res = $form->getMenus('mall');

        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'menus' => $res['menus'],
                'currentRouteInfo' => $res['currentRouteInfo'],
                'courseMenu' => $res['courseMenu']
            ]
        ]);
    }

    public function actionPlugin($name)
    {
        \Yii::$app->plugin->setCurrentPlugin(\Yii::$app->plugin->getPlugin($name));
        $route = \Yii::$app->request->post('route');

        $form = new MenusForm();
        $form->currentRoute = $route;
        $res = $form->pluginMenu();

        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'menus' => $res['menus'],
                'currentRouteInfo' => $res['currentRouteInfo'],
                'newMenus' => $res['newMenus'],
            ]
        ]);
    }
}
