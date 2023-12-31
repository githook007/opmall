<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/24
 * Time: 3:19 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\controllers\mall;

use app\forms\common\wechat\WechatFactory;
use app\forms\mall\wechat\KeywordReplyForm;
use app\forms\mall\wechat\KeywordRuleForm;
use app\forms\mall\wechat\KeywordRuleListForm;
use app\forms\mall\wechat\OperateForm;
use app\forms\mall\wechat\ServerForm;
use app\forms\mall\wechat\SettingForm;
use app\forms\mall\wechat\SubscribeReplyForm;
use app\forms\mall\wechat\VideoForm;
use app\forms\mall\wechat\WechatMenuForm;
use app\forms\open3rd\ExtAppForm;
use app\forms\open3rd\Open3rd;
use app\models\Model;
use app\models\WechatWxmpprograms;
use app\models\WxappPlatform;
use yii\web\BadRequestHttpException;

class WechatController extends MallController
{
    public function actionAuthorizer()
    {
        try {
            $platform = WxappPlatform::getPlatform();
            if (!$platform || empty($platform->component_access_token)) {
                throw new \Exception('未配置微信开放平台或者未收到推送ticket,请等待10分钟后再试');
            }
            $open3rd = new Open3rd([
                'appId' => $platform->appid,
                'appSecret' => $platform->appsecret,
                'componentAccessToken' => $platform->component_access_token,
                'auth_type' => 1
            ]);
            $res = $open3rd->getAuthorizerInfo();
            \Yii::error($res);
            usleep(500000); // 休息半秒，因为第三方消息通知也有处理下面逻辑，基本不会冲突
            $t = \Yii::$app->db->beginTransaction();
            $third = WechatFactory::getThird($res['authorization_info']['authorizer_appid']);
            if (!$third) {
                $third = new WechatWxmpprograms();
            } elseif ($third->mall_id && $third->mall_id != \Yii::$app->mall->id) {
                throw new \Exception('该账号已经绑定过其他商城');
            }
            WechatWxmpprograms::updateAll(['is_delete' => 1, "deleted_at" => mysql_timestamp()], [
                'AND',
                [
                    'mall_id' => \Yii::$app->mall->id,
                    "is_delete" => 0
                ],
                ['<>', 'authorizer_appid', $third->authorizer_appid],
            ]);
            $third->mall_id = \Yii::$app->mall->id;
            $third->authorizer_appid = $res['authorization_info']['authorizer_appid'];
            $third->authorizer_access_token = $res['authorization_info']['authorizer_access_token'];
            $third->authorizer_refresh_token = $res['authorization_info']['authorizer_refresh_token'];
            $third->authorizer_expires = time() + 7000;
            $third->func_info = json_encode($res['authorization_info']['func_info']);
            $third->is_delete = 0;
            $third->version = 2;
            if (!$third->save()) {
                throw new \Exception((new Model())->getErrorMsg($third));
            }
            $ext = ExtAppForm::instance($third, 0, 'wechat');
            $info = $ext->getAuthorizerInfo();
            $third->nick_name = $info['authorizer_info']['nick_name'];
            $third->head_img = $info['authorizer_info']['head_img'] ?? '';
            $third->verify_type_info = $info['authorizer_info']['verify_type_info']['id'];
            $third->user_name = $info['authorizer_info']['user_name'];
            $third->qrcode_url = $info['authorizer_info']['qrcode_url'];
            $third->principal_name = $info['authorizer_info']['principal_name'];
            $third->principal_name = $info['authorizer_info']['principal_name'];
            $third->signature = $info['authorizer_info']['signature'];
            if (!$third->save()) {
                throw new \Exception((new Model())->getErrorMsg($third));
            }
            $t->commit();
            return \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['mall/wechat/setting']));
        } catch (\Exception $exception) {
            if (isset($t)) {
                $t->rollBack();
            }
            \Yii::error($exception);
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    public function actionSetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new SettingForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
            if (\Yii::$app->request->isGet) {
                $form = new SettingForm();
                return $form->getDetail();
            }
        } else {
            return $this->render('setting');
        }
    }

    public function actionVideo()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new VideoForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
            if (\Yii::$app->request->isGet) {
                $form = new VideoForm();
                return $form->getDetail();
            }
        } else {
            return $this->render('video');
        }
    }

    public function actionServer()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new ServerForm();
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
            if (\Yii::$app->request->isGet) {
                $form = new ServerForm();
                return $form->getDetail();
            }
        } else {
            return $this->render('server');
        }
    }

    public function actionRandom($num)
    {
        $form = new ServerForm();
        return $form->getString(intval($num));
    }

    public function actionReply()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new SubscribeReplyForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
            if (\Yii::$app->request->isGet) {
                $form = new SubscribeReplyForm();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('reply');
        }
    }

    public function actionOperate()
    {
        $form = new OperateForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->execute());
    }

    public function actionMenus()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new WechatMenuForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
            if (\Yii::$app->request->isGet) {
                $form = new WechatMenuForm();
                return $this->asJson($form->getDetail());
            }
        } else {
            return $this->render('menus');
        }
    }

    public function actionKeywordReply()
    {
        $form = new KeywordReplyForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    public function actionKeywordRule()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new KeywordRuleForm();
                $form->attributes = \Yii::$app->request->post();
                return $this->asJson($form->save());
            }
            if (\Yii::$app->request->isGet) {
                $form = new KeywordRuleForm();
                $form->id = \Yii::$app->request->get('id');
                return $this->asJson($form->getDetail());
            }
        }else {
            return $this->render('keyword-rule');
        }
    }

    public function actionKeywordRuleList()
    {
        $form = new KeywordRuleListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getList());
    }
}
