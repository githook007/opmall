<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com/
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/10/30
 * Time: 16:37
 */

namespace app\forms\common\wechat\config;

use app\models\WxappPlatform;
use app\models\WechatWxmpprograms;
use luweiss\Wechat\Wechat;

class ThirdWechat extends Wechat
{
    public $platform;
    public $wxmpprogram;

    public function __construct($config = [])
    {
        $this->platform = WxappPlatform::getPlatform();
        parent::__construct($config);
    }

    /**
     * @param bool $refresh
     * @return string
     * @throws \Exception
     */
    public function getAccessToken($refresh = false)
    {
        if(!$this->wxmpprogram) {
            $this->wxmpprogram = WechatWxmpprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        }
        if ($this->wxmpprogram->authorizer_expires < time()) {
            $this->wxmpprogram->updateAuthorizerAccessToken(
                $this->platform->appid,
                $this->platform->component_access_token
            );
        }
        return $this->wxmpprogram->authorizer_access_token;
    }

    public function getInfo()
    {
        if(!$this->wxmpprogram) {
            $this->wxmpprogram = WechatWxmpprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        }
        return [
            'name' => $this->wxmpprogram->nick_name,
            'logo' => $this->wxmpprogram->head_img,
            'qrcode' => $this->wxmpprogram->qrcode_url,
        ];
    }
}
