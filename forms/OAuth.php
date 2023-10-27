<?php

namespace app\forms;

//对接平台的统一登录代码  @czs

use app\bootstrap\response\ApiCode;
use app\forms\common\attachment\CommonAttachment;
use app\forms\common\CommonAdminUser;
use app\forms\common\CommonAuth;
use app\models\Mall;
use app\models\MallExtend;
use app\models\User;
use app\plugins\wxapp\models\WxappConfig;

class OAuth
{
    const OAUTH_HEADER = ['Content-Type:application/x-www-form-urlencoded']; // 请求头

    const OAUTH_PRIVATE_KEY = "-----BEGIN PRIVATE KEY-----
MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEAxWX0IJTMRxXoz0ap
/eKAaVKIKNv4O0JDaH/P/HkllziedndSRviDig/i676D+XdhZAk+EwWUAFfRc7eU
eX8o6wIDAQABAkBXzNdfPo/19lrNByNJV9vB/QGvGtg4k2qkKmV6aPA9aBs5SyNA
kk9c2s+rSYTK/poEblvJrgXXPqPD0ROSF8qBAiEA8C3yo87q+db19ZvFzU+8b53C
SWudLN5b+6OmaLG1TksCIQDSZppQ44hmoYkRxmo2plP9tY15eZoLOZ7/t5wysMRr
4QIgWg4QZYVHK6iDDrV6pDHaiMtRNvBHvbQeLys8URMY1SMCIGmnmGpyN/bBu2Ev
r/qNf7PxzX9orxBf+RYUj91sotZBAiEA3fp8+NHab3D9oc8/w04A7JHQn6Zojqbo
EcAzKI0tiM0=
-----END PRIVATE KEY-----"; // 私钥

    static $oauth_conf = [
        "getUserInfo" => "/oauth/getUserInfo", // 获取用户基本信息地址
        "changePassword" => "/oauth/modifyUserPwd", //修改密码地址
    ];
    static $day = [ // 1年；2月；3日
        1 => "365", // 1年365天
        2 => "30", // 1月30天
    ];

    //获取用户信息
    public static function getUserInfo($token, &$msg = ''){
        if(empty($token)){
            return false;
        }
        if(empty($wsDomain = \Yii::$app->request->get("wsDomain"))){
            return false;
        }
        $currentUrl = $wsDomain.self::$oauth_conf["getUserInfo"]; //获取用户基本信息地址
        $loginToken = explode("-", $token);
        if(!isset($loginToken[1])){
            $msg = "免登录token有误";
            return false;
        }
        $result = self::curlRequest($currentUrl, ["token" => $loginToken[1]], self::OAUTH_HEADER);
        if($result["code"] === 0 && isset($result["data"])){
            return $result["data"];
        }
        $msg = $result['msg'];
        return false;
    }
    
    public static function getUnitByDayCount($dayCount, $type = 1){
        if($type == "3"){
            $result["unit"] = 3;
            $result["time"] = $dayCount;
        }else if($type && $type < 3){
            if ($dayCount % self::$day[$type] == "0") {
                $result["unit"] = $type;
                $result["time"] = $dayCount / self::$day[$type];
            } else {
                $result = self::getUnitByDayCount($dayCount, ++$type);
            }
        }else{
            $result = ["unit" => 0, "time" => 0];
        }
        return $result;
    }

    public static function getPermission($validTime, $params){
        $goods_limit_num = 100;
        $permissions_arr = $template = [];
        if(!isset($params["unit"]) || !isset($params["time"])){
            $params = self::getUnitByDayCount(ceil(($validTime - time()) / 86400));
        }
        if($params["unit"] == "2" && ($params["time"] == "1" || $params["time"] == "6")){ // 1、6月 只体验 小程序模块，基础版
            $permissions_arr = ["wxapp", 'wxplatform'];
        }
        if(empty($permissions_arr)) {
            $permissions = CommonAuth::getPermissionsList();
            $key = 0;
            foreach ($permissions['mall'] as $mall_k => $mall_v) {
                $permissions_arr[$mall_k] = $mall_v['name'];
                $key = $mall_k;
            }
            $key++;
            foreach ($permissions['plugins'] as $plugins_k => $plugins_v) {
                $permissions_arr[$plugins_k + $key] = $plugins_v['name'];
            }
            $permissions_arr = array_diff($permissions_arr, [
                "aliapp", "bdapp", "ttapp", "mch", "copyright", "attachment", 'assistant',
                'community', 'fast-create-wxapp', 'mobile', "wechat", "pc_manage", 'invoice',
                "minishop", 'app', 'wechat_manage', 'teller', 'scrm'
            ]); // 营销版
            $permissions_arr = array_values($permissions_arr);
            $template = ["is_all" => "1", "use_all" => "1"];
        }
        if(!empty($params['is_pack_app'])){
            $permissions_arr[] = 'app';
        }
        return [$permissions_arr, $template, $goods_limit_num];
    }

    public static function passport($loginToken, $validTime = 0, $id = 0, $params = []){
        $msg = "域名：".\Yii::$app->request->hostInfo;
        $userData = self::getUserInfo($loginToken, $msg);
        if($userData) {
            $account = $userData["uid"] . (!empty($id) ? $id : "");
            /** @var User $user */
            $user = User::find()->alias('u')->andWhere(['u.username' => $account, 'u.is_delete' => 0])->one();
            $expired = !empty($validTime) ? date("Y-m-d H:i:s", $validTime) : '0000-00-00 00:00:00';
            if (!$user) {
                //注册
                try {
                    $res = self::getPermission($validTime, $params);
                    CommonAdminUser::createAdminUser([
                        'username' => $account,
                        'password' => $userData["account"], // 以前的默认密码是  123456a
                        'mobile' => $userData["account"],
                        'app_max_count' => 1,
                        'remark' => "平台交付系统",
                        'we7_user_id' => 0,
                        'expired_at' => $expired,
                        'permissions' => $res[0],
                        'secondary_permissions' => ['attachment' => CommonAttachment::getCommon()->getDefaultAuth(), "template" => $res[1]], // 新加模板权限
                    ]);
                } catch (\Exception $e) {
                    return [
                        'code' => ApiCode::CODE_ERROR, 'msg' => $msg.'，服务器错误:' . $e->getMessage(), 'error' => ['line' => $e->getLine()]
                    ];
                }
                /** @var User $user */
                $user = User::find()->alias('u')->andWhere(['u.username' => $account, 'u.is_delete' => 0])->one();

                $model = new Mall();
                $model->name = $params["name"];
                $model->user_id = $user->id;
                $model->expired_at = $expired;
                if (!$model->save()) {
                    return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg. '，' . (isset($model->errors) ? current($model->errors)[0] : '数据异常！')];
                }
                $extend = new MallExtend();
                $extend->mall_id = $model->id;
                $extend->goods_limit_num = $res[2];
                if (!$extend->save()) {
                    return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg. '，' . (isset($extend->errors) ? current($extend->errors)[0] : '数据异常！')];
                }
                if($params["appId"]) {
                    $wxAppConfig = new WxappConfig();
                    $wxAppConfig->mall_id = $model->id;
                    $wxAppConfig->appid = $params["appId"];
                    $wxAppConfig->appsecret = $params["secret"];
                    $wxAppConfig->key = "xx";
                    $wxAppConfig->mchid = "xx";
                    $wxAppConfig->save();
                }
            }else{
                if(!$model = Mall::find()->where(["user_id" => $user->id])->one()){
                    $res = self::getPermission($validTime, $params);
                    $model = new Mall();
                    $model->name = $params["name"];
                    $model->user_id = $user->id;
                    $model->expired_at = $expired;
                    if (!$model->save()) {
                        return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg. '，' . (isset($model->errors) ? current($model->errors)[0] : '数据异常！')];
                    }
                    $extend = new MallExtend();
                    $extend->mall_id = $model->id;
                    $extend->goods_limit_num = $res[2];
                    if (!$extend->save()) {
                        return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg. '，' . (isset($extend->errors) ? current($extend->errors)[0] : '数据异常！')];
                    }
                }
            }

          	// 加判断是为了排除员工账号
            if ($user->identity->is_admin === 0 && $user->identity->is_super_admin === 0) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg.'，账户异常：账户信息不存在'];
            }
            $adminInfo = $user->adminInfo;
            if(!$adminInfo){
                return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg.'，账户异常：admin信息不存在'];
            }else{
	            if($adminInfo->expired_at != $expired){
	                $adminInfo->expired_at = $expired;
	                $adminInfo->save();
	            }
	            if ($user->identity->is_admin === 1 && $adminInfo->expired_at !== '0000-00-00 00:00:00' && time() > strtotime($adminInfo->expired_at)) {
	                return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg.'，账户已过期！请联系管理员'];
	            }
            }
            return ['code' => ApiCode::CODE_SUCCESS, 'msg' => '成功', "data" => [
                "url" => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . "?r=interface/login",
                "version" => app_version(),
                "mall_id" => $model->id,
            ]];
        }else{
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg];
        }
    }

    public static function login($loginToken, $id = 0){ 
        $userData = self::getUserInfo($loginToken, $msg);
        if($userData) {
            $account = $userData["uid"] . (!empty($id) ? $id : "");
            /** @var User $user */
            $user = User::find()->alias('u')->joinWith(['identity i'])
                ->select("u.*")
                ->andWhere(['u.username' => $account, 'u.is_delete' => 0])->one();
            if (!$user) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '用户信息不存在，请联系客服'];
            }
            $adminInfo = $user->adminInfo;
            if (!$adminInfo) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '账户异常：账户信息不存在'];
            }
            if ($user->identity->is_admin === 1 && $adminInfo->expired_at !== '0000-00-00 00:00:00' && time() > strtotime($adminInfo->expired_at)) {
                return ['code' => ApiCode::CODE_ERROR, 'msg' => '账户已过期！请联系管理员'];
            }
            \Yii::$app->user->login($user, 86400);
            $user->setLoginData(User::LOGIN_ADMIN);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '登录成功',
                'data' => ['url' => 'admin/index/index']
            ];
        }else{
            return ['code' => ApiCode::CODE_ERROR, 'msg' => $msg];
        }
    }

    public static function curlRequest($requestUrl, $requestArgs, $header = ['Content-Type: application/json'], $type = 1, $timeout = 30)
    {
        if($type){ //post请求
            $requestArgs = http_build_query($requestArgs, "", "&");
        }else{
            if($requestArgs && is_string($requestArgs)){
                $requestUrl = $requestUrl . "?" . $requestArgs;
            }else{
                $requestUrl = $requestUrl . "?" . http_build_query($requestArgs, "", "&");
            }
            $requestArgs = '';
        }
        $output = self::curl($requestUrl, $requestArgs, $header, $timeout);
        $output = json_decode($output, 1) ? json_decode($output, 1) : $output;
        return $output;
    }

    public static function curl($url, $data = "", $header = ['Content-Type: application/json'], $timeout = 60)
    {
        $ch = curl_init($url);
        if(empty($data)){
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }else {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        if(!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $output = curl_exec($ch);
        $errno = curl_errno($ch);
        if ($errno) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $output;
    }

    // 生成签名
    public static function getSign($params){
        unset($params['sign']);
        ksort($params);
        reset($params);

        $pairs = array();
        foreach ($params as $k => $v) {
            if(!empty($v)){
                $pairs[] = "$k=$v";
            }
        }
        $params = implode('&', $pairs);
        $priKeyId = openssl_pkey_get_private(self::OAUTH_PRIVATE_KEY);
        $signature = '';
        openssl_sign($params, $signature, $priKeyId, OPENSSL_ALGO_MD5);
        openssl_free_key($priKeyId);
        return base64_encode($signature);
    }
}