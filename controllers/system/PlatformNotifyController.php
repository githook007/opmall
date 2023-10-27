<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/9/22
 * Time: 9:02
 */

namespace app\controllers\system;

use app\controllers\Controller;
use app\forms\common\order\weixin\NotifyForm;
use app\forms\open3rd\ExtAppForm;
use app\forms\open3rd\Open3rd;
use app\models\Mall;
use app\models\Model;
use app\models\WxappPlatform;
use app\plugins\wechat\models\WechatWxmpprograms;
use app\plugins\wxapp\models\WxappFastCreate;
use app\plugins\wxapp\models\WxappWxminiprogramAudit;
use app\plugins\wxapp\models\WxappWxminiprograms;
use luweiss\Wechat\WechatHelper;
use yii\web\Response;

class PlatformNotifyController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function actionWechat()
    {
        \Yii::error('========平台回调========');
        \Yii::$app->response->format = Response::FORMAT_XML;
        $xml = \Yii::$app->request->rawBody;
        \Yii::warning($xml);
        $res = WechatHelper::xmlToArray($xml);
        if (!$res) {
            throw new \Exception('请求数据错误: ' . $xml);
        }
        $platform = WxappPlatform::getPlatform();
        require __DIR__ . '/../../forms/open3rd/wxmsg/wxBizMsgCrypt.php';
        $prpcrypt = new \Prpcrypt($platform->encoding_aes_key);
        $postData = $prpcrypt->decrypt($res['Encrypt'], $platform->appid);
        if ($postData[0] != 0) {
            return $postData[0];
        } else {
            $msg = $postData[1];
            $msgArray = WechatHelper::xmlToArray($msg);
            \Yii::warning($msgArray);
            $infoType = $msgArray['InfoType'] ?? $msgArray['MsgType'] ?? '';
            if ($infoType == "unauthorized") {
                $appId = $msgArray['AuthorizerAppid'];
                try {
                    $third = WxappWxminiprograms::findOne(['authorizer_appid' => $appId, 'is_delete' => 0]);
                    if ($third) {
                        $third->is_delete = 1;
                        if (!$third->save()) {
                            throw new \Exception((new Model())->getErrorMsg($third));
                        }
                        echo 'success';
                        return true;
                    }
                } catch (\Exception $e) {
                }
                try {
                    $third = WechatWxmpprograms::findOne(['authorizer_appid' => $appId, 'is_delete' => 0]);
                    if ($third) {
                        $third->is_delete = 1;
                        if (!$third->save()) {
                            throw new \Exception((new Model())->getErrorMsg($third));
                        }
                        echo 'success';
                        return true;
                    }
                } catch (\Exception $e) {
                }
            } elseif ($infoType == "component_verify_ticket") {
                //微信官方推送的ticket值
                $platform = WxappPlatform::getPlatform();
                if (!$platform) {
                    \Yii::warning('第三方平台未设置');
                }else {
                    $open3rd = new Open3rd([
                        'appId' => $platform->appid,
                        'appSecret' => $platform->appsecret
                    ]);
                    $open3rd->setComponentVerifyTicket($msgArray['ComponentVerifyTicket']);
                    if ($platform->token_expires < time()) {
                        $platform->isLog = false; // 不记录日志了
                        $token = $open3rd->getComponentAccessToken();
                        $platform->component_access_token = $token;
                        $platform->token_expires = time() + 5800;
                        if (!$platform->save()) {
                            throw new \Exception((new Model())->getErrorMsg($platform));
                        }
                    }
                }
                echo 'success';
            } elseif ($infoType == "updateauthorized") {
                try {
                    $platform = WxappPlatform::getPlatform();
                    if (!$platform) {
                        \Yii::warning('第三方平台未设置');
                        echo 'success';
                        return false;
                    }
                    $open3rd = new Open3rd([
                        'appId' => $platform->appid,
                        'appSecret' => $platform->appsecret,
                        'componentAccessToken' => $platform->component_access_token
                    ]);

                    $res = $open3rd->getAuthorizerInfo($msgArray['AuthorizationCode']);
                    if (!$res) {
                        throw new \Exception('获取授权信息失败');
                    }
                    try {
                        $third = WxappWxminiprograms::findOne(['authorizer_appid' => $res['authorization_info']['authorizer_appid'], 'is_delete' => 0]);
                        if (!$third) {
                            $third = WechatWxmpprograms::findOne(['authorizer_appid' => $res['authorization_info']['authorizer_appid'], 'is_delete' => 0]);
                            if (!$third) {
                                throw new \Exception('该小程序或公众号暂未绑定商城');
                            }
                        }
                    } catch (\Error $error) {
                    }
                    $third->authorizer_appid = $res['authorization_info']['authorizer_appid'];
                    $third->authorizer_access_token = $res['authorization_info']['authorizer_access_token'];
                    $third->authorizer_refresh_token = $res['authorization_info']['authorizer_refresh_token'];
                    $third->authorizer_expires = time() + 7000;
                    $third->func_info = json_encode($res['authorization_info']['func_info']);
                    $third->is_delete = 0;
                    $mall = Mall::findOne($third->mall_id);
                    if (!$mall) {
                        throw new \Exception('未查询到id=' . $third->id . '的商城。 ');
                    }
                    \Yii::$app->setMall($mall);
                    echo 'success';

                    $info = ExtAppForm::instance()->getAuthorizerInfo();
                    if (!$info) {
                        throw new \Exception('获取授权方的帐号基本信息失败');
                    }
                    $third->nick_name = $info['authorizer_info']['nick_name'];
                    $third->head_img = $info['authorizer_info']['head_img'];
                    $third->verify_type_info = $info['authorizer_info']['verify_type_info']['id'];
                    $third->user_name = $info['authorizer_info']['user_name'];
                    $third->qrcode_url = $info['authorizer_info']['qrcode_url'];
                    $third->principal_name = $info['authorizer_info']['principal_name'];
                    $third->signature = $info['authorizer_info']['signature'];
                    if (!$third->save()) {
                        throw new \Exception((new Model())->getErrorMsg($third));
                    }
                } catch (\Exception $exception) {
                    \Yii::error($exception);
                }
            } elseif ($infoType == 'event') {
                try {
                    \Yii::warning('审核事件回调');
                    if (isset($msgArray['Event'])) {
                        switch ($msgArray['Event']){
                            case 'weapp_audit_success': // 小程序审核通过
                                $appId = \Yii::$app->request->pathInfo;
                                \Yii::warning($appId);
                                $extApp = WxappWxminiprograms::findOne(['authorizer_appid' => $appId, 'is_delete' => 0]);
                                $mall = Mall::findOne($extApp->mall_id);
                                if (!$mall) {
                                    throw new \Exception('未查询到id=' . $extApp->id . '的商城。 ');
                                }
                                \Yii::$app->setMall($mall);
                                $ext = ExtAppForm::instance($extApp);
                                $ext->release();
                                $audit = WxappWxminiprogramAudit::find()
                                    ->where(['appid' => $extApp->authorizer_appid, 'status' => [0, 2, 3]])
                                    ->orderBy('id desc')
                                    ->one();
                                if ($audit) {
                                    $audit->status = 4;
                                    $audit->release_at = mysql_timestamp();
                                    $audit->save();
                                }
                                break;
                            case "open_product_order_pay": // 微信自定义交易组件支付通知
                                \app\plugins\minishop\forms\common\CommonNotify::getInstance()->handleOrderPay(
                                    (array)$msgArray['order_info'], true
                                );
                                break;
                            // 用户提交售后申请 @czs
                            case 'aftersale_new_order':
                                \app\plugins\minishop\forms\common\CommonNotify::getInstance()->handleCreateAftersale(
                                    (array)$msgArray['aftersale_info'], true
                                );
                                break;
                            case 'aftersale_wait_merchant_confirm_receipt': // 待商家确认收货 @czs
                            case 'aftersale_update_order': // 更新售后申请 @czs
                                \app\plugins\minishop\forms\common\CommonNotify::getInstance()->handleUpdateAftersale(
                                    (array)$msgArray['aftersale_info'], true
                                );
                                break;
                            // 取消售后申请 @czs
                            case 'aftersale_user_cancel':
                                \app\plugins\minishop\forms\common\CommonNotify::getInstance()->handleCancelAftersale(
                                    (array)$msgArray['aftersale_info'], true
                                );
                                break;
                            // ====   小程序发货信息管理服务  =====
                            // 提醒接入发货信息管理服务API @czs
                            case 'trade_manage_remind_access_api':
                                (new NotifyForm($msgArray))->remindAccessApi();
                                break;
                            // 提醒需要上传发货信息 @czs
                            case 'trade_manage_remind_shipping':
                                (new NotifyForm($msgArray))->remindShipping();
                                break;
                            // 订单将要结算或已经结算 @czs
                            case 'trade_manage_order_settlement':
                                (new NotifyForm($msgArray))->remindOrderSettlement();
                                break;
                        }
                    }
                    echo 'success';
                } catch (\Exception $exception) {
                    \Yii::warning('审核事件回调失败');
                    \Yii::error($exception);
                }
            } elseif ($infoType == 'notify_third_fasteregister') {
                try {
                    \Yii::warning('注册审核事件推送');
                    $tempInfo = (array)$msgArray['info'];
                    $name = $tempInfo['name'];
                    $code = $tempInfo['code'];
                    $legal_persona_wechat = $tempInfo['legal_persona_wechat'];
                    $legal_persona_name = $tempInfo['legal_persona_name'];
                    $md5 = md5(json_encode([
                        'name' => $name,
                        'code' => $code,
                        'legal_persona_wechat' => $legal_persona_wechat,
                        'legal_persona_name' => $legal_persona_name,
                    ]));
                    /** @var WxappFastCreate $app */
                    $app = WxappFastCreate::find()->where(['md5' => $md5, 'is_delete' => 0])->orderBy('id DESC')->one();
                    if (!$app) {
                        throw new \Exception('注册审核的小程序不存在');
                    }
                    $app->status = $msgArray['status'];
                    if (isset($msgArray['appid'])) {
                        $app->appid = $msgArray['appid'];
                    }
                    if (isset($msgArray['auth_code'])) {
                        $app->auth_code = $msgArray['auth_code'];
                    }
                    if (!$app->save()) {
                        throw new \Exception((new Model())->getErrorMsg($app));
                    }
                    $wxappPrograms = WxappWxminiprograms::findOne(['authorizer_appid' => $app->appid, 'is_delete' => 0]);
                    if($wxappPrograms && !$wxappPrograms->mall_id){
                        $wxappPrograms->mall_id = $app->mall_id;
                        $wxappPrograms->save();
                    }
                    echo 'success';
                } catch (\Exception $exception) {
                    \Yii::warning('注册审核事件推送失败');
                    \Yii::error($exception);
                }
            } elseif ($infoType == "notify_third_fastverifybetaapp") { // 试用小程序快速认证结果通知
                ExtAppForm::positiveWxapp($msgArray);
                echo 'success';
            } elseif ($infoType == "notify_third_fastregisterbetaapp") { // 快速体验小程序
                ExtAppForm::fastRegisterBetaapp($msgArray);
                echo 'success';
            } elseif ($infoType == "authorized") {
                ExtAppForm::handleAuthorized($msgArray);
                echo 'success';
            }
        }
    }
}
