<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * User: jack_guo
 * Date: 2019/7/4
 * Time: 11:22
 */

namespace app\plugins\stock\controllers\api;

use app\bootstrap\response\ApiCode;
use app\plugins\stock\models\StockSetting;

class ApiController extends \app\controllers\api\ApiController
{
    public function beforeAction($action)
    {
        //权限判断
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        if (!in_array('stock', $permission)) {
            \Yii::$app->response->data = ['code' => ApiCode::CODE_ERROR, 'msg' => '无股东分红权限'];
            return false;
        }
        //判断分红开关
        $model = StockSetting::findOne(['mall_id' => \Yii::$app->mall->id, 'key' => 'is_stock', 'value' => '1', 'is_delete' => 0]);
        if (empty($model)) {
            \Yii::$app->response->data = ['code' => ApiCode::CODE_ERROR, 'msg' => '股东分红已关闭'];
            return false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}