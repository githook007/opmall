<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/1
 * Time: 10:36
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms;

use app\bootstrap\response\ApiCode;
use app\forms\common\attachment\CommonAttachment;
use app\forms\common\CommonAdminUser;
use app\forms\common\CommonOption;
use app\forms\common\CommonQrCode;
use app\forms\open3rd\ExtAppForm;
use app\helpers\ArrayHelper;
use app\helpers\CurlHelper;
use app\models\Mall;
use app\models\Model;
use app\models\Option;
use app\models\User;
use app\models\UserIdentity;
use app\plugins\wxapp\models\WxappTrial;
use app\plugins\wxapp\models\WxappWxminiprograms;

class WxServerForm extends Model
{
    // 基本信息
    public $name;
    public $appId;
    public $mall_id;
    public $instanceId;
    public $uid;

    // 服务市场步骤
    public $step;

    // 通知平台的参数
    public $token;
    public $requestUrl;

    // 申请体验小程序的参数
    public $legal_persona_idcard;
    public $legal_persona_name;
    public $legal_persona_wechat;
    public $code;
    public $enterprise_name;
    public $code_type;
    public $component_phone;

    // 完善小程序信息
    public $wxapp_name;
    public $wxapp_avatar;
    public $wxapp_category;
    public $wxapp_desc;
    public $license_pic;

    public function rules()
    {
        return [
            [['appId', 'name', 'uid', 'legal_persona_idcard', 'legal_persona_name', 'legal_persona_wechat',
                'enterprise_name', 'code', 'component_phone', 'token', 'requestUrl', 'wxapp_avatar',
                'wxapp_name', 'wxapp_category', 'wxapp_desc', 'license_pic'], 'string'],
            [['mall_id', 'instanceId', "step", "code_type"], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'appId' => '试用小程序appid',
            'name' => '试用小程序',
        ];
    }

    public function createMall()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            /** @var User $user */
            $user = User::find()->alias("u")
                ->leftJoin(['ui' => UserIdentity::tableName()], 'ui.user_id = u.id')
                ->where(['ui.is_super_admin' => 1, "ui.is_delete" => 0])
                ->one();
            if(!$user){
                throw new \Exception('超管用户不存在');
            }
            $mall = new Mall();
            $mall->name = $this->name;
            $mall->user_id = $user->id;
            $mall->expired_at = date("Y-m-d H:i:s", time() + (7*86400));
            if (!$mall->save()) {
                throw new \Exception($this->getErrorMsg($mall));
            }

            $third = WxappWxminiprograms::findOne([
                'authorizer_appid' => $this->appId,
            ]);
            if($third){
                $third->mall_id = $mall->id;
                $third->is_delete = 0;
                if (!$third->save()) {
                    throw new \Exception($this->getErrorMsg($third));
                }
                $wxappTrial = WxappTrial::findOne(["appid" => $this->appId]);
                if(!$wxappTrial){
                    $wxappTrial = new WxappTrial();
                    $wxappTrial->appid = $this->appId;
                    $wxappTrial->source = WxappTrial::WX_SERVER_SOURCE;
                }
                $wxappTrial->is_delete = 0;
                if (!$wxappTrial->save()) {
                    throw new \Exception($this->getErrorMsg($wxappTrial));
                }
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                "data" => [
                    "mall_id" => $mall->id,
                    "url" => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . "?r=interface/login",
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function qrcode()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $mall = Mall::findOne($this->mall_id);
            if(!$mall){
                throw new \Exception('商城不存在');
            }
            \Yii::$app->setMall($mall);

            $third = WxappWxminiprograms::findOne([
                'authorizer_appid' => $this->appId,
                'mall_id' => \Yii::$app->mall->id
            ]);

            $content = ExtAppForm::instance($third)->getExpVersion();
            $experience_code = '';
            if($content){
                $name = md5(base64_encode($content)) . '.jpg';
                $save_path = \Yii::$app->basePath . '/web/temp/' . $name;
                if (!is_dir(\Yii::$app->basePath . '/web/temp')) {
                    mkdir(\Yii::$app->basePath . '/web/temp');
                }
                $fp = fopen($save_path, 'w');
                fwrite($fp, $content);
                fclose($fp);
                $experience_code = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/temp/{$name}";
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                "data" => [
                    "qrCode" => $experience_code,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function updateMall()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $mall = Mall::findOne($this->mall_id);
            if(!$mall){
                throw new \Exception('商城不存在');
            }
            $user = $this->checkUser();

            $mall->user_id = $user->id;
            $mall->expired_at = date("Y-m-d H:i:s", time() + (7*86400));
            if (!$mall->save()) {
                throw new \Exception($this->getErrorMsg($mall));
            }

            $third = WxappWxminiprograms::findOne([
                'authorizer_appid' => $this->appId,
            ]);
            if($third){
                $third->mall_id = $mall->id;
                $third->is_delete = 0;
                if (!$third->save()) {
                    throw new \Exception($this->getErrorMsg($third));
                }
                $serverDomain = [
                    'action' => 'set',
                    'requestdomain' => [
                        'https://' . \Yii::$app->request->hostName
                    ],
                    'wsrequestdomain' => [
                        'wss://' . \Yii::$app->request->hostName
                    ],
                    'uploaddomain' => [
                        'https://' . \Yii::$app->request->hostName
                    ],
                    'downloaddomain' => [
                        'https://' . \Yii::$app->request->hostName
                    ],
                ];
                (ExtAppForm::instance($third))->setServerDomain(json_encode($serverDomain));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                "data" => [
                    "mall_id" => $mall->id,
                    "url" => \Yii::$app->request->hostInfo . \Yii::$app->request->scriptUrl . "?r=interface/login",
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function checkUser($source = '微信服务市场'){
        $account = $this->uid . $this->instanceId;
        $user = User::find()->alias('u')
            ->leftJoin(['ui' => UserIdentity::tableName()], 'ui.user_id = u.id')
            ->andWhere(['u.username' => $account, 'u.is_delete' => 0, "ui.is_admin" => 1])
            ->select("u.*")
            ->one();
        if (!$user) {
            $expired = date("Y-m-d H:i:s", time() + 7 * 86400);
            //注册
            $adminInfo = CommonAdminUser::createAdminUser([
                'username' => $account,
                'password' => $this->uid,
                'mobile' => $this->uid,
                'app_max_count' => 1,
                'remark' => $source,
                'we7_user_id' => 0,
                'expired_at' => $expired,
                'permissions' => ["wxapp", 'wxplatform'],
                'secondary_permissions' => [
                    'attachment' => CommonAttachment::getCommon()->getDefaultAuth(),
                    "template" => ["is_all" => "1", "use_all" => "1"]
                ] // 新加模板权限
            ]);
            $user = $adminInfo->user;
        }
        return $user;
    }

    public function handleWxappCertification(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $t = \Yii::$app->db->beginTransaction();
        try {
            $mall = Mall::findOne($this->mall_id);
            if(!$mall){
                throw new \Exception('商城不存在');
            }
            \Yii::$app->setMall($mall);

            $ext = ExtAppForm::instance();

            switch ($this->step){
                case 1: // 企业信息认证
                case 2: // 企业信息认证
                    $wxappTrial = WxappTrial::findOne(["appid" => $ext->authorizer_appid]);
                    if(!$wxappTrial){
                        $wxappTrial = new WxappTrial();
                        $wxappTrial->type = 1;
                        $wxappTrial->status = 1;
                    }
                    $wxappTrial->is_delete = 0;
                    $wxappTrial->attributes = $this->attributes;
                    $wxappTrial->appid = $ext->authorizer_appid;
                    $wxappTrial->notify_url = $this->requestUrl;
                    if(!$wxappTrial->save()){
                        throw new \Exception($this->getErrorMsg($wxappTrial));
                    }
                    $res = $ext->verifyBetaweapp([
                        'legal_persona_name' => $this->legal_persona_name,
                        "enterprise_name" => $this->enterprise_name,
                        "code" => $this->code,
                        "code_type" => $this->code_type,
                        "legal_persona_wechat" => $this->legal_persona_wechat,
                        "legal_persona_idcard" => $this->legal_persona_idcard,
                        "component_phone" => $this->component_phone,
                    ]);
                    break;
                case 3: //
                    break;
                default:
                    throw new \Exception("错误的步骤");
            }

            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                'data' => $res ?? ''
            ];
        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function getTrialApp(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try{
            $web = CommonOption::get(Option::NAME_WX_PLATFORM_WEB, 0, Option::GROUP_ADMIN);
            if(empty($web['mp_app_id']) || empty($web['mp_app_secret'])){
                throw new \Exception('第三方网站公众号未配置');
            }
            if($this->code){
                $args = [
                    "appid" => $web['mp_app_id'],
                    "secret" => $web['mp_app_secret'],
                    "code" => $this->code,
                    "grant_type" => 'authorization_code',
                ];
                $params = '';
                foreach ($args as $key => $item) {
                    $params .= $key . '=' . $item . '&';
                }
                $url = "https://api.weixin.qq.com/sns/oauth2/access_token" . '?' . rtrim($params, '&');
                $accessTokenRes = CurlHelper::getInstance()->httpGet($url);
                if(!isset($accessTokenRes['openid'])){
                    throw new \Exception('获取用户openid失败');
                }
                $ext = ExtAppForm::instance(null, 1);
                $res = $ext->fastRegisterBetaweapp([
                    "name" => '试用店铺'.rand(1000, 9999),
                    "openid" => $accessTokenRes['openid']
                ]);
                if($res->errcode !== 0){
                    throw new \Exception($res->errmsg);
                }
                \Yii::$app->cache->set($res->unique_id, $this->token, 43200);
                return \Yii::$app->response->redirect($res->authorize_url);
            }else{
                if(!\Yii::$app->cache->get($this->token)){
                    throw new \Exception('二维码已失效');
                }
                $args = [
                    "appid" => $web['mp_app_id'],
                    "redirect_uri" => urlencode(\Yii::$app->request->absoluteUrl),
                    "response_type" => "code",
                    "scope" => 'snsapi_base',
                    "state" => ''
                ];
                $params = '';
                foreach ($args as $key => $item) {
                    $params .= $key . '=' . $item . '&';
                }
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize" . '?' . rtrim($params, '&') . '#wechat_redirect';
                return \Yii::$app->response->redirect($url);
            }
        }catch (\Exception $e){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getWxappPositive(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $user = $this->checkUser('极速体验小程序');
            $mall = Mall::findOne(['user_id' => $user->id, 'is_delete' => 0]);
            if(!$mall){
                throw new \Exception("商城不存在");
            }
            \Yii::$app->setMall($mall);
            $ext = ExtAppForm::instance();

            $wxappTrial = WxappTrial::findOne(["appid" => $ext->authorizer_appid]);
            if(!$wxappTrial){
                $wxappTrial = new WxappTrial();
                $wxappTrial->type = 1;
                $wxappTrial->status = 1;
                $wxappTrial->source = WxappTrial::PLATFORM_SOURCE;
            }
            $wxappTrial->is_delete = 0;
            $wxappTrial->attributes = $this->attributes;
            $wxappTrial->appid = $ext->authorizer_appid;
            $wxappTrial->notify_url = $this->requestUrl;
            if(!$wxappTrial->save()){
                throw new \Exception($this->getErrorMsg($wxappTrial));
            }
            $params = [
                'legal_persona_name' => $this->legal_persona_name,
                "enterprise_name" => $this->enterprise_name,
                "code" => $this->code,
                "code_type" => $this->code_type,
                "legal_persona_wechat" => $this->legal_persona_wechat,
                "legal_persona_idcard" => $this->legal_persona_idcard,
                "component_phone" => $this->component_phone,
            ];
            if(empty($params['component_phone'])){
                unset($params['component_phone']);
            }
            $res = $ext->verifyBetaweapp($params);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                'data' => $res ?? ''
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function getWxappCategories(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $user = $this->checkUser('极速体验小程序');
            $mall = Mall::findOne(['user_id' => $user->id, 'is_delete' => 0]);
            if(!$mall){
                throw new \Exception("商城不存在");
            }
            \Yii::$app->setMall($mall);
            $res = ExtAppForm::instance()->getAllCategories();
            $res = ArrayHelper::toArray($res->categories_list->categories);
            unset($res[0]);
            $arr = ArrayHelper::index($res, null, "level");
            $data = [];
            foreach ($arr[1] as $item){
                if($item['sensitive_type'] == 1){ // 1 为敏感类目，需要提供相应资质审核；0 为非敏感类目，无需审核
                    continue;
                }
                $new = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'children' => [],
                ];
                $data[$item['id']] = $new;
            }
            foreach ($arr[2] as $item){
                if($item['sensitive_type'] == 1){ // 1 为敏感类目，需要提供相应资质审核；0 为非敏感类目，无需审核
                    continue;
                }
                if(!isset($data[$item['father']])){
                    continue;
                }
                $new = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                ];
                $data[$item['father']]['children'][] = $new;
            }
            foreach ($data as $k => $item){
                if(empty($item['children'])){
                    unset($data[$k]);
                }
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                'data' => array_values($data)
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function updateWxappInfo(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $user = $this->checkUser('极速体验小程序');
            $mall = Mall::findOne(['user_id' => $user->id, 'is_delete' => 0]);
            if(!$mall){
                throw new \Exception("商城不存在");
            }
            \Yii::$app->setMall($mall);
            $ext = ExtAppForm::instance();

            $wxappTrial = WxappTrial::findOne(["appid" => $ext->authorizer_appid]);
            if(!$wxappTrial){
                $wxappTrial = new WxappTrial();
                $wxappTrial->type = 1;
                $wxappTrial->status = 1;
                $wxappTrial->source = WxappTrial::PLATFORM_SOURCE;
            }
            $wxappTrial->is_delete = 0;
            if($this->wxapp_name) {
                $wxappTrial->wxapp_name = $this->wxapp_name;
            }
            if($this->license_pic) {
                $wxappTrial->license_pic = $this->license_pic;
            }
            if ($this->wxapp_desc) {
                $wxappTrial->wxapp_desc = $this->wxapp_desc;
            }
            if ($this->wxapp_category) {
                $wxappTrial->wxapp_category = $this->wxapp_category;
            }
            if ($this->wxapp_avatar) {
                $wxappTrial->wxapp_avatar = $this->wxapp_avatar;
            }
            if($wxappTrial->oldAttributes['wxapp_category'] != $wxappTrial->wxapp_category) {
                // 添加类目
                $categories = explode(",", $wxappTrial->wxapp_category);
                $catData = $temp = [];
                foreach ($categories as $item) {
                    if (count($temp) == 2) {
                        $catData[] = ['first' => $temp[0], 'second' => $temp[1]];
                        $temp = [];
                    }
                    $temp[] = $item;
                }
                $ext->addCategory(['categories' => $catData]);
            }
            if($wxappTrial->oldAttributes['wxapp_name'] != $wxappTrial->wxapp_name) {
                // 设置小程序名称
                $ext->setNickname([
                    'nick_name' => $wxappTrial->wxapp_name,
                    'license' => $ext->uploadMedia($wxappTrial->license_pic)
                ]);
            }
            if($wxappTrial->oldAttributes['wxapp_desc'] != $wxappTrial->wxapp_desc) {
                // 设置小程序介绍
                $ext->modifySignature(['signature' => $wxappTrial->wxapp_desc]);
            }
            if($wxappTrial->oldAttributes['wxapp_avatar'] != $wxappTrial->wxapp_avatar) {
                // 修改头像
                $ext->modifyHeadImage([
                    'head_img_media_id' => $ext->uploadMedia($wxappTrial->wxapp_avatar, 2),
                    'x1' => '0',
                    'y1' => '0',
                    'x2' => '0.7596899224806202',
                    'y2' => '0.49',
                ]);
            }
            if(!$wxappTrial->save()){
                throw new \Exception($this->getErrorMsg($wxappTrial));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function toPublish(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $user = $this->checkUser('极速体验小程序');
            $mall = Mall::findOne(['user_id' => $user->id, 'is_delete' => 0]);
            if(!$mall){
                throw new \Exception("商城不存在");
            }
            \Yii::$app->setMall($mall);
            \Yii::$app->user->login($user, 86400);
            $user->setLoginData(User::LOGIN_ADMIN);
            \Yii::$app->setSessionMallId($mall->id);
            return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['plugin/wxapp/wx-app-config/setting']));
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function handleForNotify(){
        \Yii::$app->setAppPlatform("wxapp");
        $res = CommonQrCode::instance()->getQrCode();
        if ($this->requestUrl) {
            $params = [
                'id' => $this->instanceId,
                "qrCode" => $res['file_path'] ?? '',
                'appId' => $this->appId,
                'uid' => $this->uid,
            ];
            $params['qrCode'] = str_replace("platform-notify", "", $params['qrCode']);
            $params['sign'] = OAuth::getSign($params);
            $res = CurlHelper::getInstance()->httpPost($this->requestUrl, [], $params);
            if($res['code'] != 0){
                throw new \Exception($res['msg']);
            }
        }
    }
}
