<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\wxapp\forms\wx_app_config;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\wxapp\models\WxappConfig;
use luweiss\Wechat\Wechat;
use luweiss\Wechat\WechatException;

class WxAppConfigEditForm extends Model
{
    public $appid;
    public $appsecret;
    public $id;
    public $wx_mini_upload_key;

    public function rules()
    {
        return [
            [['appid', 'appsecret'], 'required'],
            [['appid', 'appsecret', 'wx_mini_upload_key'], 'string'],
            [['id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'appid' => '小程序AppId',
            'appsecret' => '小程序appSecret',
            'wx_mini_upload_key' => "小程序上传密钥"
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        /**@var WxappConfig $wxAppConfig * */
        $wxAppConfig = WxappConfig::find()
            ->where(['mall_id' => \Yii::$app->mall->id])
            ->one();
        if (!$wxAppConfig) {
            $wxAppConfig = new WxappConfig();
        }

        try {
            $wechat = new Wechat(
                [
                    'appId' => $this->appid,
                    'appSecret' => $this->appsecret,
                ]
            );
            $wechat->getAccessToken(true);
        } catch (WechatException $e) {
            if ($e->getRaw()['errcode'] == '40013') {
                $message = '小程序AppId有误(' . $e->getRaw()['errmsg'] . ')';
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $message,
                ];
            }
            if ($e->getRaw()['errcode'] == '40125') {
                $message = '小程序appSecret有误(' . $e->getRaw()['errmsg'] . ')';
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $message,
                ];
            }
        }

        $t = \Yii::$app->db->beginTransaction();
        try {
            $wxAppConfig->mall_id = \Yii::$app->mall->id;
            $wxAppConfig->appid = $this->appid;
            $wxAppConfig->appsecret = $this->appsecret;
            $wxAppConfig->wx_mini_upload_key = $this->wx_mini_upload_key;
            $res = $wxAppConfig->save();
            if ($res) {
                $t->commit();
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '保存成功',
                ];
            }

            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '保存失败',
            ];
        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
