<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/9/25
 * Time: 17:35
 */

namespace app\forms\open3rd;

use app\forms\common\wechat\WechatFactory;
use app\forms\WxServerForm;
use app\jobs\PublicExtApp;
use app\models\Mall;
use app\models\Model;
use app\models\ModelActiveRecord;
use app\models\WechatWxmpprograms;
use app\models\WxappPlatform;
use app\plugins\wxapp\forms\ThirdWechat;
use app\plugins\wxapp\forms\wx_app_config\WxAppConfigForm;
use app\plugins\wxapp\models\WxappTrial;
use app\plugins\wxapp\models\WxappWxminiprograms;

/**
 * Class ExtAppForm
 * @package app\forms\open3rd
 */
class ExtAppForm
{
    /**
     * @param $extApp
     * @param int $is_platform
     * @param string $plugin
     * @return ExtApp
     * @throws Open3rdException
     * @throws \luweiss\Wechat\WechatException
     */
    public static function instance($extApp = null, $is_platform = 0, $plugin = 'wxapp')
    {
        $open3rd = WxappPlatform::getPlatform();

        if (!$open3rd || empty($open3rd->component_access_token)) {
            throw new \Exception('未配置微信开放平台或者未收到推送ticket,请等待10分钟后再试');
        }
        if ($is_platform) {
            return new ExtApp([
                'thirdAppId' => $open3rd->appid,
                'thirdToken' => $open3rd->token,
                'thirdAccessToken' => $open3rd->component_access_token,
                'is_platform' => 1,
            ]);
        }
        if ($extApp === null) {
            $extApp = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        }
        if (!$extApp) {
            throw new \Exception('尚未授权');
        }

        $config = [
            'thirdAppId' => $open3rd->appid,
            'thirdToken' => $open3rd->token,
            'thirdAccessToken' => $open3rd->component_access_token,
            'authorizer_appid' => $extApp->authorizer_appid,
            'plugin' => $plugin,
        ];
        if($plugin == 'wxapp'){
            $wechat = new ThirdWechat([
                'appId' => $extApp->authorizer_appid,
                'miniprogram' => $extApp
            ]);
            $config['authorizer_access_token'] = $wechat->getAccessToken();
        }
        return new ExtApp($config);
    }

    // @czs
    public static function operatePrivacy($type = 2, $extApp = null, $get = false){
        $form = new WxAppConfigForm();
        $listData = $form->getOption();

        if(!$listData){
            throw new \Exception('微信第三方隐私协议不存在');
        }
        if(!$listData['owner_setting']['contact_phone'] && !$listData['owner_setting']['notice_method']){
            throw new \Exception('微信第三方隐私协议未配置');
        }
        try {
            if(!$extApp) {
                $extApp = self::instance();
            }
            if($get){
                return $extApp->getPrivacySetting($type);
            }else {
                $extApp->setPrivacySetting($listData['owner_setting'], $listData['setting_list'], $type);
            }
        }catch (\Exception $e){
            \Yii::error($e);
        }

        return true;
    }

    // @czs 快速体验小程序
    public static function fastRegisterBetaapp($msgArray){
        \Yii::warning('快速体验小程序成功通知了：'.$msgArray['appid']);
        try{
            ModelActiveRecord::$log = false;
            usleep(2500000); // 休息2.5秒
            $info = (array)$msgArray['info'];
            $token = \Yii::$app->cache->get($info['unique_id']);
            $data = \Yii::$app->cache->get($token);
            if($data && is_array($data)){
                $form = new WxServerForm();
                $form->attributes = $data;
                $user = $form->checkUser('平台交付');
                $mall = Mall::findOne(['user_id' => $user->id, 'is_delete' => 0]);
                if(!$mall){
                    $mall = new Mall();
                    $mall->name = $form->name ?: '';
                    $mall->user_id = $user->id;
                    $mall->expired_at = date("Y-m-d H:i:s", time() + (7*86400));
                    if (!$mall->save()) {
                        throw new \Exception((new Model())->getErrorMsg($mall));
                    }
                }
                if(WxappWxminiprograms::updateAll(['is_delete' => 1], ['mall_id' => $mall->id, 'is_delete' => 0])){
                    \Yii::warning('商城已有小程序绑定，但已软删除，mall_id:'.$mall->id);
                }
                $third = WxappWxminiprograms::findOne(['authorizer_appid' => $msgArray['appid'], 'is_delete' => 0]);
                if (!$third) {
                    $third = new WxappWxminiprograms();
                    $third->domain = 'https://' . \Yii::$app->request->hostName;
                    $third->authorizer_appid = $msgArray['appid'];
                }
                $third->mall_id = $mall->id;
                if (!$third->save()) {
                    throw new \Exception((new Model())->getErrorMsg($third));
                }
                $form->appId = $third->authorizer_appid;
                \Yii::$app->setMall($mall);
                \Yii::$app->queue->delay(0)->push(new PublicExtApp([
                    'mall' => $mall,
                    'requestUrl' => $form->requestUrl,
                    'instanceId' => $form->instanceId,
                    'uid' => $form->uid,
                    'appId' => $form->appId,
                ]));
                $form->handleForNotify();
            }
            \Yii::$app->cache->delete($info['unique_id']);
            \Yii::$app->cache->delete($token);
        }catch (\Exception $e){
            \Yii::error('快速体验小程序失败');
            \Yii::error($e);
        }
    }

    // 第三方消息通知接口处理授权信息
    public static function handleAuthorized($msgArray){
        \Yii::warning('授权信息开始了');

        $platform = WxappPlatform::getPlatform();
        if (!$platform || empty($platform->component_access_token)) {
            throw new \Exception('未配置微信开放平台或者未收到推送ticket,请等待10分钟后再试');
        }
        ModelActiveRecord::$log = false;
        try {
            $open3rd = new Open3rd([
                'appId' => $platform->appid,
                'appSecret' => $platform->appsecret,
                'componentAccessToken' => $platform->component_access_token,
            ]);
            $res = $open3rd->getAuthorizerInfo($msgArray['AuthorizationCode']);

            $ext = new ExtApp([
                'thirdAppId' => $platform->appid,
                'thirdToken' => $platform->token,
                'thirdAccessToken' => $platform->component_access_token,
                'authorizer_appid' => $res['authorization_info']['authorizer_appid'],
                'is_platform' => 1
            ]);
            $info = $ext->getAuthorizerInfo();
//            \Yii::warning($info);

            $isWxApp = false;
            if(!empty($info['authorizer_info']['MiniProgramInfo'])) { // 小程序授权
                \Yii::warning('小程序授权信息');
                $third = WxappWxminiprograms::findOne(['authorizer_appid' => $msgArray['AuthorizerAppid'], 'is_delete' => 0]);
                if (!$third) {
                    $third = new WxappWxminiprograms();
                    $third->domain = 'https://' . \Yii::$app->request->hostName;
                    $third->mall_id = 0;
                }
                $isWxApp = true;
            }else{ // 公众号授权
                \Yii::warning('公众号授权信息');
                $third = WechatFactory::getThird($res['authorization_info']['authorizer_appid']);
                if (!$third) {
                    $third = new WechatWxmpprograms();
                    $third->version = 2;
                    $third->mall_id = 0;
                }
            }
            if (!$third->save()) {
                throw new \Exception((new Model())->getErrorMsg($third));
            }

            $third->authorizer_appid = $res['authorization_info']['authorizer_appid'];
            $third->authorizer_access_token = $res['authorization_info']['authorizer_access_token'];
            $third->authorizer_refresh_token = $res['authorization_info']['authorizer_refresh_token'];
            $third->authorizer_expires = time() + 7000;
            $third->func_info = json_encode($res['authorization_info']['func_info']);
            $third->is_delete = 0;
            $third->nick_name = $info['authorizer_info']['nick_name'];
            $third->head_img = $info['authorizer_info']['head_img'] ?? '';
            $third->verify_type_info = $info['authorizer_info']['verify_type_info']['id'];
            $third->user_name = $info['authorizer_info']['user_name'];
            $third->qrcode_url = $info['authorizer_info']['qrcode_url'];
            $third->principal_name = $info['authorizer_info']['principal_name'];
            $third->signature = $info['authorizer_info']['signature'];
            if (!$third->save()) {
                throw new \Exception((new Model())->getErrorMsg($third));
            }

            if($isWxApp) {
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
                $wechat = new ThirdWechat([
                    'appId' => $third->authorizer_appid,
                    'miniprogram' => $third
                ]);
                $ext->authorizer_access_token = $wechat->getAccessToken();
                $ext->setServerDomain(json_encode($serverDomain));
            }
        } catch (\Exception $exception) {
            \Yii::error('授权信息失败');
            \Yii::error($exception);
        }
    }

    public static function positiveWxapp($msgArray) {
        \Yii::warning('第三方小程序转正开始了');

        try{
            $wxappTrial = WxappTrial::findOne(["appid" => $msgArray['appid']]);
            if(!$wxappTrial){
                throw new \Exception('试用小程序信息不存在');
            }
            if(isset($msgArray['info'])) {
                $data = (array)$msgArray['info'];
                $wxappTrial->attributes = $data;
                $wxappTrial->enterprise_name = $data['name'];
                if(empty($data['component_phone'])){
                    $wxappTrial->component_phone = '';
                }else{
                    $wxappTrial->component_phone = (string)($wxappTrial->component_phone);
                }
            }
            $wxappTrial->is_delete = 0;
            $wxappTrial->status = $msgArray['status'] == 0 ? 2 : $msgArray['status'];
            $wxappTrial->status_msg = OpenErrorCode::errorMsg($msgArray['status'], $msgArray['msg'] ?? '');
            if(!$wxappTrial->save()){
                throw new \Exception((new Model())->getErrorMsg($wxappTrial));
            }
            $wxappTrial->notify();
        }catch (\Exception $e){
            \Yii::error('小程序转正失败');
            \Yii::error($e);
        }
    }
}
