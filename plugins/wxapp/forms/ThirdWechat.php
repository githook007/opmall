<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/9/25
 * Time: 9:40
 */

namespace app\plugins\wxapp\forms;

use app\models\WxappPlatform;
use app\plugins\wxapp\models\WxappWxminiprograms;
use luweiss\Wechat\Wechat;

class ThirdWechat extends Wechat
{
    public $platform;
    public $miniprogram;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->platform = WxappPlatform::getPlatform();
    }

    /**
     * @param bool $refresh
     * @return string
     * @throws \Exception
     */
    public function getAccessToken($refresh = false)
    {
        if(!$this->miniprogram) {
            $this->miniprogram = WxappWxminiprograms::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
        }
        if ($this->miniprogram->authorizer_expires < time()) {
            $this->miniprogram->updateAuthorizerAccessToken(
                $this->platform->appid,
                $this->platform->component_access_token
            );
        }
        return $this->miniprogram->authorizer_access_token;
    }

    public function jsCodeToSession($code)
    {
        $api = "https://api.weixin.qq.com/sns/component/jscode2session?appid={$this->appId}&js_code={$code}&grant_type=authorization_code&component_appid={$this->platform->appid}&component_access_token={$this->platform->component_access_token}";
        return $this->getClient()->get($api);
    }
}
