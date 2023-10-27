<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/10/13
 * Time: 10:09
 */

namespace app\forms\admin\platform;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\models\WxappPlatform;

class PlatformSettingEditForm extends Model
{
    public $appid;
    public $appsecret;
    public $token;
    public $encoding_aes_key;
    public $uploaddomain;
    public $downloaddomain;
    public $webviewdomain;

    public $mp_app_id;
    public $mp_app_secret;

    public function rules()
    {
        return [
            [['appid', 'appsecret', 'token', 'encoding_aes_key'], 'required'],
            [['appid', 'mp_app_id'], 'string', 'max' => 128],
            [['appsecret', 'token', 'mp_app_secret'], 'string', 'max' => 255],
            [['encoding_aes_key'], 'string', 'max' => 512],
            [['uploaddomain', 'downloaddomain', 'webviewdomain'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'appid' => 'Appid',
            'appsecret' => 'Appsecret',
            'token' => 'Token',
            'encoding_aes_key' => 'Encoding Aes Key',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $domain = $this->dealDomain();
        $platform = WxappPlatform::getPlatform();
        if (!$platform) {
            $platform = new WxappPlatform();
        }
        $platform->appid = $this->appid;
        $platform->appsecret = $this->appsecret;
        $platform->token = $this->token;
        $platform->encoding_aes_key = $this->encoding_aes_key;
        $platform->domain = json_encode($domain, JSON_UNESCAPED_UNICODE);
        $res = $platform->save();
        if (!$res) {
            throw new \Exception((new Model())->getErrorMsg($platform));
        }
        CommonOption::set(Option::NAME_WX_PLATFORM_WEB, [
            'mp_app_id' => $this->mp_app_id,
            'mp_app_secret' => $this->mp_app_secret,
        ], 0, Option::GROUP_ADMIN);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    private function dealDomain()
    {
        $domain = [
            'uploaddomain' => '',
            'downloaddomain' => '',
            'webviewdomain' => ''
        ];
        if ($this->uploaddomain) {
            $domain['uploaddomain'] = $this->uploaddomain;
        }
        if ($this->downloaddomain) {
            $domain['downloaddomain'] = $this->downloaddomain;
        }
        if ($this->webviewdomain) {
            $domain['webviewdomain'] = $this->webviewdomain;
        }
        return $domain;
    }
}
