<?php
/**
 * @copyright ©2020 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/2/12
 * Time: 9:31
 */

namespace app\plugins\pick\controllers\api;


use app\bootstrap\response\ApiCode;

class ApiController extends \app\controllers\api\ApiController
{
    public function beforeAction($action)
    {
        //权限判断
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        if (!in_array('pick', $permission)) {
            \Yii::$app->response->data = ['code' => ApiCode::CODE_ERROR, 'msg' => '无N元任选权限'];
            return false;
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}
