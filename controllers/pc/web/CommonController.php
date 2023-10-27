<?php
/**
* link: https://www.opmall.com/
* copyright: Copyright (c)
* author: opmall
*/

namespace app\controllers\pc\web;

use app\controllers\Controller;
use app\controllers\pc\web\filters\BlackListFilter;
use app\controllers\pc\web\filters\MallDisabledFilter;
use app\models\Mall;
use app\forms\common\share\CommonShare;
use app\models\pc\UserLogin;
use app\models\StatisticsDataLog;
use app\models\StatisticsUserLog;
use yii\filters\Cors;
use yii\web\NotFoundHttpException;

class CommonController extends Controller
{
    public $safeRoutes = [];

    public function init()
    {
        parent::init();
        if (property_exists(\Yii::$app, 'appIsRunning') === false) {
            exit('property not found.');
        }
        $this->enableCsrfValidation = false;
        try {
            $this->setMall()->login();//->bindParent();
        }catch (\Exception $e){
            \Yii::$app->response->data = ["code" => -1, "msg" => $e->getMessage()];
            \Yii::$app->response->send();
        }
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'disabled' => [
                'class' => MallDisabledFilter::class,
            ],
            'blackList' => [
                'class' => BlackListFilter::class
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ], // @czs 跨域请求
        ]);
    }

    public function getParams($key = null, $default = null){
        if(\Yii::$app->request->isGet){
            $params = \Yii::$app->request->get($key, $default);
        }else{
            if($key){
                if(!$params = \Yii::$app->request->post($key)){
                    $params = \Yii::$app->request->get($key, $default);
                }
            }else{
                $params = \Yii::$app->request->get(null, $default);
                unset($params['r']);
                $params = array_merge($params, \Yii::$app->request->post(null, $default));
            }
        }
        return $params;
    }

    private function setMall()
    {
        $mallId = base64_decode(urldecode($this->getParams('mall_id')));
        $mallId = $mallId ?: 1;
        $mall = Mall::findOne(['id' => $mallId, 'is_delete' => 0, 'is_recycle' => 0]);
        if (!$mall) {
            throw new NotFoundHttpException('mall_id=' . $mallId."的商城不存在");
        }
        \Yii::$app->setMall($mall);
        return $this;
    }

    private function login()
    {
        $accessToken = $this->getParams("token");
        //访问量记录
        $this->setVisits();

        if (!$accessToken) {
            return $this;
        }
        $loginInfo = UserLogin::findOne(["token" => $accessToken, "mall_id" => \Yii::$app->mall->id]);
        if($loginInfo && $loginInfo->user && $loginInfo->expire_time > time()){
            \Yii::$app->user->login($loginInfo->user, 86400);
            //访问人数记录
            $this->setUserLog();
        }else{
            \Yii::$app->user->logout();
        }
        return $this;
    }

    private function bindParent()
    {
        if (\Yii::$app->user->isGuest) {
            return $this;
        }
        $userId = $this->getParams("p_user_id");
        if (!$userId) {
            return $this;
        }
        $common = CommonShare::getCommon();
        $common->mall = \Yii::$app->mall;
        $common->user = \Yii::$app->user->identity;
        try {
            $common->bindParent($userId, 1);
        } catch (\Exception $exception) {
            \Yii::error($exception->getMessage());
            $userInfo = $common->user->userInfo;
            $userInfo->temp_parent_id = $userId;
            $userInfo->save();
        }
        return $this;
    }

    private $second = 5;//记录间隔秒

    //记录访问数
    private function setVisits()
    {
        $data_log = StatisticsDataLog::find()
            ->andWhere(['and', ['mall_id' => \Yii::$app->mall->id], ['like', 'created_at', date('Y-m-d')], ['key' => 'visits']])
            ->one();
        if (empty($data_log)) {
            $data_log = new StatisticsDataLog();
            $data_log->mall_id = \Yii::$app->mall->id;
            $data_log->key = 'visits';
            $data_log->value = 1;
            $data_log->time_stamp = time();
            $data_log->save();
        } elseif (bcsub(time(), $data_log->time_stamp) > $this->second) {
            $data_log->updateCounters(['value' => 1]);
            $data_log->time_stamp = time();
            $data_log->save();
        }
    }

    //记录访客数
    private function setUserLog()
    {
        $user_log = StatisticsUserLog::find()
            ->andWhere(['mall_id' => \Yii::$app->mall->id, 'user_id' => \Yii::$app->user->id, 'is_delete' => 0])
            ->andWhere(['like', 'created_at', date('Y-m-d')])
            ->one();
        if (empty($user_log)) {
            $user_log = new StatisticsUserLog();
            $user_log->mall_id = \Yii::$app->mall->id;
            $user_log->user_id = \Yii::$app->user->id;
            $user_log->num = 1;
            $user_log->time_stamp = time();
            $user_log->save();
        } elseif (bcsub(time(), $user_log->time_stamp) > $this->second) {
            $user_log->updateCounters(['num' => 1]);
            $user_log->time_stamp = time();
            $user_log->save();
        }
    }
}
