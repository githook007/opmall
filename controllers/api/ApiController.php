<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 18:19
 */


namespace app\controllers\api;

use app\controllers\api\filters\BlackListFilter;
use app\controllers\api\filters\MallDisabledFilter;
use app\controllers\api\filters\WechatFilter;
use app\controllers\Controller;
use app\forms\common\share\CommonShare;
use app\helpers\EncryptHelper;
use app\models\Formid;
use app\models\Mall;
use app\models\StatisticsDataLog;
use app\models\StatisticsUserLog;
use app\models\User;
use app\models\We7App;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiController extends Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'disabled' => [
                'class' => MallDisabledFilter::class,
            ],
            'blackList' => [
                'class' => BlackListFilter::class
            ],
            'wechat' => [
                'class' => WechatFilter::class
            ],
//            'corsFilter' => [
//                'class' => Cors::class,
//            ], // @czs 跨域请求
        ]);
    }

    public function init()
    {
        parent::init();
//        if(\Yii::$app->appVersion == '[object Object]' || \Yii::$app->appVersion == '4.4.8') { // 后续可以删除了
//            \Yii::$app->setAppVersion('2.3.8'); // @czs 版本号赋值
//        }
        $this->enableCsrfValidation = false;
        $this->setMall()->login()->saveFormIdList()->bindParent();
        //绑定beforeSend事件，更改数据格式 @czs 暂时不需要
        \Yii::$app->getResponse()->on(Response::EVENT_BEFORE_SEND, [$this, 'beforeSend']);
    }

    public function beforeSend($event){
        if(\Yii::$app->appPlatform == APP_PLATFORM_APP) { // app端才返回 czs
            /** @var Response $response */
            $response = $event->sender;
            $response->data['statusCode'] = 200;
            $response->data['requestData'] = [
                'post' => \Yii::$app->request->post(),
                "get" => \Yii::$app->request->get(),
            ];
            if (!isset($response->data['msg'])) {
                $response->data['msg'] = '请求成功';
            }
        }
    }

    private function setMall()
    {
        $acid = \Yii::$app->request->get('_acid');
        if ($acid && $acid > 0) {
            $we7app = We7App::findOne([
                'acid' => $acid,
                'is_delete' => 0,
            ]);
            $mallId = $we7app ? $we7app->mall_id : null;
        } else {
            $mallId = \Yii::$app->request->get('_mall_id');
            if(\Yii::$app->appPlatform == APP_PLATFORM_APP){ // app端 @czs
                $mallId = EncryptHelper::getNum($mallId);
            }
        }
        $mall = Mall::findOne([
            'id' => $mallId,
            'is_delete' => 0,
            'is_recycle' => 0,
        ]);
        if (!$mall) {
            throw new NotFoundHttpException('商城不存在，id = ' . $mallId);
        }
        \Yii::$app->setMall($mall);
        return $this;
    }

    private function login()
    {
        $headers = \Yii::$app->request->headers;
        $accessToken = empty($headers['x-access-token']) ? null : $headers['x-access-token'];

        //访问量记录
        $this->setVisits();

        if (!$accessToken) {
            return $this;
        }
        $user = User::findOne([
            'access_token' => $accessToken,
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ]);

        if ($user) {
            \Yii::$app->user->login($user);
            //访问人数记录
            $this->setUserLog();
        }
        return $this;
    }

    private function saveFormIdList()
    {
        if (\Yii::$app->user->isGuest) {
            return $this;
        }
        if (empty(\Yii::$app->request->headers['x-form-id-list'])) {
            return $this;
        }
        $rawData = \Yii::$app->request->headers['x-form-id-list'];
        $list = json_decode($rawData, true);
        if (!$list || !is_array($list) || !count($list)) {
            return $this;
        }
        foreach ($list as $item) {
            $formid = new Formid();
            $formid->user_id = \Yii::$app->user->id;
            $formid->form_id = $item['value'];
            $formid->remains = $item['remains'];
            $formid->expired_at = $item['expires_at'];
            $formid->save();
        }
        return $this;
    }

    private function bindParent()
    {
        if (\Yii::$app->user->isGuest) {
            return $this;
        }
        $headers = \Yii::$app->request->headers;
        $userId = empty($headers['x-user-id']) ? null : $headers['x-user-id'];
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
        /** @var StatisticsDataLog[] $data_log */
        $data_log = StatisticsDataLog::find()
            ->andWhere([
                'and',
                ['mall_id' => \Yii::$app->mall->id],
                ['>=', 'created_at', date('Y-m-d') . ' 00:00:00'],
                ['<=', 'created_at', date('Y-m-d') . ' 23:59:59'],
                ['key' => 'visits']
            ])->all();
        if (count($data_log) == 0) {
            $data_log = new StatisticsDataLog();
            $data_log->mall_id = \Yii::$app->mall->id;
            $data_log->key = 'visits';
            $data_log->value = 1;
            $data_log->time_stamp = time();
            $data_log->save();
        } elseif (bcsub(time(), $data_log[0]->time_stamp) > $this->second) {
            $data_log[0]->time_stamp = time();
            $data_log[0]->value = $data_log[0]->value + 1;
            $data_log[0]->save();
            unset($data_log[0]);
            foreach ($data_log as $log){
                $log->delete();
            }
        }
    }

    //记录访客数
    private function setUserLog()
    {
        /** @var StatisticsUserLog[] $user_log */
        $user_log = StatisticsUserLog::find()
            ->andWhere([
                'and',
                ['mall_id' => \Yii::$app->mall->id, 'user_id' => \Yii::$app->user->id, 'is_delete' => 0],
                ['>=', 'created_at', date('Y-m-d') . ' 00:00:00'],
                ['<=', 'created_at', date('Y-m-d') . ' 23:59:59'],
            ])->all();
        if (count($user_log) == 0) {
            $user_log = new StatisticsUserLog();
            $user_log->mall_id = \Yii::$app->mall->id;
            $user_log->user_id = \Yii::$app->user->id;
            $user_log->num = 1;
            $user_log->time_stamp = time();
            $user_log->save();
        } elseif (bcsub(time(), $user_log[0]->time_stamp) > $this->second) {
            $user_log[0]->time_stamp = time();
            $user_log[0]->num = $user_log[0]->num + 1;
            $user_log[0]->save();
            unset($user_log[0]);
            foreach ($user_log as $log){
                $log->delete();
            }
        }
    }
}

