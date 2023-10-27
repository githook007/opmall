<?php

namespace app\controllers;

use app\bootstrap\response\ApiCode;
use app\forms\OAuth;
use app\forms\open3rd\WxRecode;
use app\forms\WxServerForm;
use app\helpers\EncryptHelper;
use app\models\AdminInfo;
use app\models\Mall;
use app\models\MallExtend;
use app\models\User;
use app\models\UserVisit;
use app\plugins\wxapp\models\WxappConfig;
use yii\db\Expression;
use yii\helpers\Json;

// @czs  创建账号并自动创建应用
class InterfaceController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    // 微信服务市场创建商城
    public function actionCreateMall(){
        $form = new WxServerForm();
        $data = Json::decode(file_get_contents('php://input'));
        $form->attributes = $data;
        $sign = $this->getSign($form->attributes);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        return $form->createMall();
    }

    // 微信服务市场更新商城
    public function actionUpdateMall(){
        $form = new WxServerForm();
        $data = Json::decode(file_get_contents('php://input'));
        $form->attributes = $data;
        $sign = $this->getSign($form->attributes);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        return $form->updateMall();
    }

    // 微信服务市场认证小程序
    public function actionHandleMall(){
        $form = new WxServerForm();
        $data = Json::decode(file_get_contents('php://input'));
        $form->attributes = $data;
        $sign = $this->getSign($form->attributes);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        return $form->handleWxappCertification();
    }

    public function actionTrialAppUrl(){
        $data = Json::decode(file_get_contents('php://input'));
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        $token = \Yii::$app->security->generateRandomString(16);
        \Yii::$app->cache->set($token, $data, 43200);
        return $this->asJson(['code' => 0, 'data' => [
            'url' => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . "?r=site/trial-app&token={$token}",
        ]]);
    }

    public function actionWxappPositive(){
        $data = Json::decode(file_get_contents('php://input'));
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        $form = new WxServerForm();
        $form->attributes = $data;
        return $form->getWxappPositive();
    }

    public function actionWxappCategories(){
        $data = Json::decode(file_get_contents('php://input'));
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        $form = new WxServerForm();
        $form->attributes = $data;
        return $form->getWxappCategories();
    }

    public function actionUpdateWxappInfo(){
        $data = Json::decode(file_get_contents('php://input'));
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        \Yii::warning("更新小程序基本信息参数：".var_export($data, true));
        $form = new WxServerForm();
        $form->attributes = $data;
        return $form->updateWxappInfo();
    }

    public function actionWxappPublish(){
        $data = \Yii::$app->request->get();
        unset($data["r"]);
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        $form = new WxServerForm();
        $form->attributes = $data;
        return $form->toPublish();
    }

    public function actionQrcode(){
        $data = Json::decode(file_get_contents('php://input'));
        unset($data["r"]);
        $sign = $this->getSign($data);
        if ($sign != $data['sign']) {
            return $this->asJson(['code' => 1, 'msg' => "签名错误"]);
        }
        unset($data['sign']);
        $form = new WxServerForm();
        $form->attributes = $data;
        return $form->qrcode();
    }

    // 获取内存
    public function actionGetMemory(){
        $loginToken = \Yii::$app->request->get("loginToken");
        if(empty($loginToken)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $res = OAuth::login($loginToken, \Yii::$app->request->get("id"));
        if($res["code"] !== 0) {
            return $this->asJson(['code' => 1, 'msg' => $res["msg"]]);
        }else{
            $data = (Mall::findOne(['user_id' => \Yii::$app->user->id, 'is_delete' => 0]))->extendObj();
            return $this->asJson(['code' => 0, 'msg' => "成功", 'data' => [
                'memory' => $data->memory / 1024,
                'used_memory' => round($data->used_memory / 1024, 8),
            ]]);
        }
    }

    // 新增内存
    public function actionAddMemory(){
        $loginToken = \Yii::$app->request->get("loginToken");
        $memory = \Yii::$app->request->get("memory");
        $type = \Yii::$app->request->get("type");  // 类型   1新增  2直接赋值
        if(empty($loginToken) || empty($memory)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $res = OAuth::login($loginToken, \Yii::$app->request->get("id"));
        if($res["code"] !== 0) {
            return $this->asJson(['code' => 1, 'msg' => $res["msg"]]);
        }else{
            if ($type == 2){  // 直接赋值
                (Mall::findOne(['user_id' => \Yii::$app->user->id, 'is_delete' => 0]))->extendObj(['memory' => intval($memory * 1024)]);
            }else{
                $extend = (Mall::findOne(['user_id' => \Yii::$app->user->id, 'is_delete' => 0]))->extendObj();
                $extend->memory += intval($memory * 1024);
                $extend->save();
            }
            return $this->asJson(['code' => 0, 'msg' => "成功"]);
        }
    }

    // 创建账号和商户
    public function actionIndex()
    {
        $params = \Yii::$app->request->get();
        unset($params["r"]);
        try {
            $sign = $this->getSign($params);
            if ($sign != $params["sign"]) {
                return $this->asJson(['code' => -1, 'msg' => "签名错误"]);
            }
            $res = OAuth::passport($params["token"], $params["validTime"], $params["id"], $params);
            return $this->asJson($res);
        }catch (\Exception $e){
            \Yii::error("自动交付功能错误");
            \Yii::error($e);
            return $this->asJson(['code' => -1, 'msg' => $e->getMessage()]);
        }
    }

    // 自动登录
    public function actionLogin()
    {
        $loginToken = \Yii::$app->request->get("loginToken");
        if(empty($loginToken)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $res = OAuth::login($loginToken, \Yii::$app->request->get("id"));
        if($res["code"] !== 0) {
            return $this->asJson(['code' => 1, 'msg' => $res["msg"]]);
        }else{
            /** @var Mall $mallData */
            $mallData = Mall::find()->where(["user_id" => \Yii::$app->user->id])->one();
            \Yii::$app->setMall($mallData);
            $domain = \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl;
            $domain = str_replace("index.php", "shoproot.php", $domain);
            if(\Yii::$app->request->get('type') == 'authorizer') {
                if (!in_array('wxapp', \Yii::$app->mall->role->permission)) {
                    $url = $domain . "?r={$res["data"]["url"]}";
                }else {
                    $url = $domain . '?r=plugin/wxapp/third-platform/authorizer';
                    \Yii::$app->setSessionMallId($mallData ? $mallData->id : 0);
                }
            }else{
                if ($mallData) {
                    $url = $domain . "?r=admin%2Fmall%2Fentry&id=" . $mallData->id;
                } else {
                    $url = $domain . "?r={$res["data"]["url"]}";
                }
            }
            return \Yii::$app->response->redirect($url);
        }
    }

    // 修改过期时间
    public function actionModify(){
        $loginToken = \Yii::$app->request->get("token");
        $id = \Yii::$app->request->get("id");
        if(empty($loginToken)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $userData = OAuth::getUserInfo($loginToken, $msg);
        if($userData) {
            $userData["account"] = (empty($userData["uid"]) ? $userData["account"] : $userData["uid"]) . (!empty($id) ? $id : "");
            $user = User::find()->where(['username' => $userData["account"], 'is_delete' => 0])->one();
            if(!$user){
                return $this->asJson(['code' => 1, 'msg' => "用户不存在"]);
            }
            $adminInfo = AdminInfo::find()->where(['user_id' => $user->id])->one();
            $adminInfo->expired_at = date("Y-m-d H:i:s", \Yii::$app->request->get("validTime"));
            $adminInfo->save();
            $mallData = Mall::find()->where(['user_id' => $user->id])->one();
            $mallData->expired_at = date("Y-m-d H:i:s", \Yii::$app->request->get("validTime"));
            $mallData->save();
        }
        return $this->asJson(['code' => 0, 'msg' => "成功"]);
    }
    
    // 修改基本信息
    public function actionUpdate(){
        $uid = \Yii::$app->request->get("uid");
        $id = \Yii::$app->request->get("id");
        if(empty($uid) || empty($id)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $account = $uid . $id;
        $user = User::findOne(['username' => $account, 'is_delete' => 0]);
        if(!$user){
            return $this->asJson(['code' => 1, 'msg' => "用户不存在"]);
        }
        $mallData = Mall::findOne(["user_id" => $user->id]);
        if($mallData) {
            \Yii::$app->setMall($mallData);
            if(\Yii::$app->request->get("name")) {
                $mallData->name = \Yii::$app->request->get("name");
                if (!$mallData->save()) {
                    return $this->asJson(['code' => 1, 'msg' => isset($model->errors) ? current($mallData->errors)[0] : '数据异常！']);
                }
            }
            if(\Yii::$app->request->get("appId")) {
                $wxAppConfig = WxappConfig::findOne(["mall_id" => $mallData->id]);
                if (empty($wxAppConfig)) {
                    $wxAppConfig = new WxappConfig();
                    $wxAppConfig->mall_id = $mallData->id;
                    $wxAppConfig->appid = \Yii::$app->request->get("appId");
                    $wxAppConfig->appsecret = \Yii::$app->request->get("secret");
                    $wxAppConfig->key = "xx";
                    $wxAppConfig->mchid = "xx";
                    $wxAppConfig->save();
                } else {
                    $wxAppConfig->appid = \Yii::$app->request->get("appId");
                    $wxAppConfig->appsecret = \Yii::$app->request->get("secret");
                    $wxAppConfig->save();
                }
            }
            if(\Yii::$app->request->get("appData")) {
                $appData = Json::decode(\Yii::$app->request->get('appData'));
                \Yii::$app->plugin->getPlugin("app")->updateConfig($appData);
            }
        }else{
            return $this->asJson(['code' => 1, 'msg' => "商城不存在"]);
        }
        return $this->asJson(['code' => 0, 'msg' => "成功"]);
    }

    public function actionInfo(){
        $loginToken = \Yii::$app->request->get("token");
        $id = \Yii::$app->request->get("id");
        if(empty($id)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        if($loginToken) {
            $userData = OAuth::getUserInfo($loginToken, $msg);
            if ($userData) {
                $account = $userData["uid"] . $id;
            }else{
                return $this->asJson(['code' => 1, 'msg' => $msg]);
            }
        }else{
            if(empty(\Yii::$app->request->get("super"))){
                return $this->asJson(['code' => 1, 'msg' => "错误"]);
            }
            $account = \Yii::$app->request->get("super");
        }
        $user = User::findOne(['username' => $account, 'is_delete' => 0]);
        if (!$user) {
            return $this->asJson(['code' => 1, 'msg' => "用户不存在"]);
        }
        $mallData = Mall::findOne(["user_id" => $user->id]);
        \Yii::$app->setMall($mallData);
        if ($mallData) {
            return $this->asJson(['code' => 0, 'msg' => "成功", "data" => [
                "url" => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl,
                "h5" => \Yii::$app->plugin->getPlugin("mobile")->getWebUri(),
                "mall_id" => $mallData->id,
            ]]);
        } else {
            return $this->asJson(['code' => 1, 'msg' => "商城不存在"]);
        }
    }

    // 生成签名
    public function getSign($params){
        return OAuth::getSign($params);
    }
    
    public function actionGenerate(){
    	$res = ["code" => 1, "msg" => "请求成功"];
    	if(!empty(\Yii::$app->request->get("username")) && !empty(\Yii::$app->request->get("password"))){
            $encrypt = EncryptHelper::authCode(
                "u=".\Yii::$app->request->get("username")."&p=".\Yii::$app->request->get("password"),
                "",
                false
            );
    		echo \Yii::$app->request->hostInfo.\Yii::$app->request->scriptUrl."?r=interface/bintang&data=".urlencode($encrypt);exit;
    	}
    	echo json_encode($res, JSON_UNESCAPED_UNICODE);exit;
    }
    
    // 免登录
    public function actionBintang()
    {
        $loginToken = \Yii::$app->request->get("data");
        if(empty($loginToken)){
            echo json_encode(['code' => -1, 'msg' => "错误"], JSON_UNESCAPED_UNICODE);exit;
        }
        $data = EncryptHelper::authCode($loginToken);
        parse_str($data, $userData);
        /** @var User $user */
        $user = User::find()->alias('u')->joinWith(['identity'])
            ->andWhere(['u.username' => $userData["u"], 'u.is_delete' => 0])
            ->select("u.*")
            ->one();
        if (!$user) {
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '用户信息不存在，请联系客服'];
        }
        if (!\Yii::$app->getSecurity()->validatePassword($userData["p"], $user->password)) {
        	echo json_encode(['code' => -1, 'msg' => "密码错误"], JSON_UNESCAPED_UNICODE);exit;
        }
        $adminInfo = AdminInfo::find()->where(['user_id' => $user->id])->one();
        // 加判断是为了排除员工账号
        if ($user->identity->is_admin === 1 && !$adminInfo) {
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '账户异常：账户信息不存在'];
        }
        if ($adminInfo->expired_at !== '0000-00-00 00:00:00' && time() > strtotime($adminInfo->expired_at)) {
            return ['code' => ApiCode::CODE_ERROR, 'msg' => '账户已过期！请联系管理员'];
        }
        \Yii::$app->user->login($user, 86400);
        $user->setLoginData(User::LOGIN_ADMIN);
    
        $mallData = Mall::find()->where(["user_id" => \Yii::$app->user->id])->one();
        if($mallData) {
            return \Yii::$app->response->redirect(\Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . "?r=admin%2Fmall%2Fentry&id=".$mallData->id);
        } else {
            return \Yii::$app->response->redirect(\Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl);
        }
    }

    //获取用户日活跃量
    public function actionVisits(){
        $uid = \Yii::$app->request->get("uid");
        $id = \Yii::$app->request->get("id");
        if(empty($uid) || empty($id)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $account = $uid . $id;
        $user = User::findOne(['username' => $account, 'is_delete' => 0]);
        if(!$user){
            return $this->asJson(['code' => 1, 'msg' => "用户不存在"]);
        }
        $mallData = Mall::findOne(["user_id" => $user->id]);
        if($mallData) {
            $sarTime = date('Ymd', time()-86400*30);
            $endTime = date('Ymd', time());
            $data = UserVisit::find()->where(['mall_id'=>$mallData->id])->andFilterWhere(['>', 'time', $sarTime])->andFilterWhere(['<', 'time', $endTime])->orderBy('visit_uv desc')->one();
            if (!$data){
                $list = [
                    'visit_uv' => 0,
                    'time' => date('Ymd')
                ];
            }else{
                $list = [
                    'visit_uv' => $data->visit_uv,
                    'time' => $data->time
                ];
            }
            return $this->asJson(['code' => 0, 'data' => $list, 'msg' => "成功"]);
        }else{
            return $this->asJson(['code' => 1, 'msg' => "商城不存在"]);
        }
    }

    // 获取小程序码
    public function actionRecode(){
        $uid = \Yii::$app->request->get("uid");
        $id = \Yii::$app->request->get("id");
        if(empty($uid) || empty($id)){
            return $this->asJson(['code' => -1, 'msg' => "错误"]);
        }
        $account = $uid . $id;
        $user = User::findOne(['username' => $account, 'is_delete' => 0]);
        if(!$user){
            return $this->asJson(['code' => 1, 'msg' => "用户不存在"]);
        }
        $mallData = Mall::findOne(["user_id" => $user->id]);
        if($mallData) {
            \Yii::$app->setMall(Mall::findOne($mallData));
            $wxRecode = new WxRecode();
            $res = $wxRecode->getRecode();
            return $this->asJson($res);
        }else{
            return $this->asJson(['code' => 1, 'msg' => "商城不存在"]);
        }
    }
}
