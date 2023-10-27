<?php

namespace app\forms\open3rd;

use app\forms\common\wechat\service\MediaService;
use Curl\Curl;
use GuzzleHttp\Client;
use yii\base\BaseObject;

/**
 * 第三方平台代小程序实现业务
 * Class ExtApp
 * @package app\forms\open3rd
 */
class ExtApp extends BaseObject
{
    public $is_platform = 0;
    /**@var string $thirdAppId 开放平台appid**/
    public $thirdAppId;
    /**@var string $thirdToken 开放平台token**/
    public $thirdToken;
    /**@var string $thirdAccessToken 开放平台access_token**/
    public $thirdAccessToken;
    /**@var string $authorizer_appid 授权给第三方平台的小程序appid**/
    public $authorizer_appid;
    /**@var string $authorizer_appid 授权给第三方平台的小程序access_token**/
    public $authorizer_access_token;

    public $plugin = 'wxapp';

    public function init()
    {
        if (!$this->thirdAppId) {
            throw new Open3rdException('thirdAppId not null');
        }
        if (!$this->thirdToken) {
            throw new Open3rdException('thirdToken not null');
        }
        if (!$this->thirdAccessToken) {
            throw new Open3rdException('thirdAccessToken not null');
        }
        if (!$this->is_platform && !$this->authorizer_appid) {
            throw new Open3rdException('authorizer_appid not null');
        }
        if (!$this->is_platform && !$this->authorizer_access_token) {
            $plugin = \Yii::$app->plugin->getPlugin($this->plugin);
            $this->authorizer_access_token = $plugin->getAccessToken();
        }
        parent::init();
    }

    /**
     * 获取授权方的帐号基本信息
     * @return bool
     * @throws Open3rdException
     */
    public function getAuthorizerInfo()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=" . $this->thirdAccessToken;
        $data = json_encode([
            'component_appid' => $this->thirdAppId,
            'authorizer_appid' => $this->authorizer_appid
        ]);
        $ret = json_decode($this->getCurl()->post($url, $data)->response, true);
        if (isset($ret['authorizer_info'])) {
            return $ret;
        } else {
            $this->errorLog("获取授权方的帐号基本信息操作失败,appid:" . $this->authorizer_appid . $ret['errmsg'], $ret);
        }
    }

    /**
     * 设置小程序服务器地址
     * @param string $domain
     * @return bool
     */
    public function setServerDomain($data)
    {
        $url = "https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("设置小程序服务器地址失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 设置小程序业务域名
     * @param string $domain
     * @return bool
     */
    public function setBusinessDomain($data)
    {
        $url = "https://api.weixin.qq.com/wxa/setwebviewdomain?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("设置小程序业务域名失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 成员管理，绑定小程序体验者
     * @params string $wechatid : 体验者的微信号
     * */
    public function bindMember($wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/bind_tester?access_token=" . $this->authorizer_access_token;
        $data = '{"wechatid":"' . $wechatid . '"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("绑定小程序体验者操作失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 成员管理，解绑定小程序体验者
     * @params string $wechatid : 体验者的微信号
     * */
    public function unBindMember($wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/unbind_tester?access_token=" . $this->authorizer_access_token;
        $data = '{"wechatid":"' . $wechatid . '"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("解绑定小程序体验者操作失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 成员管理，获取小程序体验者列表
     * */
    public function listMember()
    {
        $url = "https://api.weixin.qq.com/wxa/memberauth?access_token=" . $this->authorizer_access_token;
        $data = '{"action":"get_experiencer"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return $ret->members;
        } else {
            $this->errorLog("获取小程序体验者列表操作失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 获取代码模板列表
     * @return mixed
     * @throws Open3rdException
     */
    public function templateList()
    {
        $url = "https://api.weixin.qq.com/wxa/gettemplatelist?access_token=" . $this->thirdAccessToken;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("获取代码模板列表失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 删除指定代码模板
     * @param $template_id
     * @return mixed
     * @throws Open3rdException
     */
    public function deletetemplate($template_id)
    {
        $url = "https://api.weixin.qq.com/wxa/deletetemplate?access_token=" . $this->thirdAccessToken;
        $data = '{"template_id":"' . $template_id . '"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("获取代码模板列表失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 获取代码草稿列表
     * @return mixed
     * @throws Open3rdException
     */
    public function templatedraftlist()
    {
        $url = "https://api.weixin.qq.com/wxa/gettemplatedraftlist?access_token=" . $this->thirdAccessToken;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("获取代码草稿列表失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 将草稿添加到代码模板库
     * @param $draft_id
     * @return mixed
     * @throws Open3rdException
     */
    public function addtotemplate($draft_id)
    {
        $url = "https://api.weixin.qq.com/wxa/addtotemplate?access_token=" . $this->thirdAccessToken;
        $data = '{"draft_id":"' . $draft_id . '"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("将草稿添加到代码模板库失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 为授权的小程序帐号上传小程序代码
     * @params int $template_id : 模板ID
     * @params json $ext_json : 小程序配置文件，json格式
     * @params string $user_version : 代码版本号
     * @params string $user_desc : 代码描述
     * */
    public function uploadCode($template_id, $is_plugin = 0, $user_version = 'v1.0.0', $user_desc = "小程序模板库")
    {
        $live = [
            "live-player-plugin" => [
                "version" =>  "1.3.2",
                "provider" => "wx2b03c6e691cd7370"
            ]
        ];
        $plugin = $is_plugin ? $live : (object)[];
        $hostInfo = (\Yii::$app->hostInfo ?: \Yii::$app->request->hostInfo);
        $domain = $hostInfo . (\Yii::$app->baseUrl ?: \Yii::$app->request->scriptUrl);
        $domain = str_replace("http://", "https://", $domain);
        $ext = [
            'extEnable' => true,
            'extAppid' => $this->authorizer_appid,
            'ext' => [
                "siteroot" => "{$domain}",
                "apiroot" => "{$domain}?_mall_id=".\Yii::$app->mall->id,
                "domain" => $hostInfo
            ],
            'plugins' => $plugin
        ];
        $ext_json = json_encode($ext);
        $url = "https://api.weixin.qq.com/wxa/commit?access_token=" . $this->authorizer_access_token;
        $data = json_encode([
            'template_id' => $template_id,
            'ext_json' => $ext_json,
            'user_version' => $user_version,
            'user_desc' => $user_desc
        ], JSON_UNESCAPED_UNICODE);
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("为授权的小程序帐号上传小程序代码操作失败,appid:" . $this->authorizer_appid . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 获取体验小程序的体验二维码
     * @params string $path :   指定体验版二维码跳转到某个具体页面
     * */
    public function getExpVersion($path = '')
    {
        if ($path) {
            $url = "https://api.weixin.qq.com/wxa/get_qrcode?access_token=" . $this->authorizer_access_token . "&path=" . urlencode(
                    $path
                );
        } else {
            $url = "https://api.weixin.qq.com/wxa/get_qrcode?access_token=" . $this->authorizer_access_token;
        }
        $result = $this->getCurl()->get($url)->response;
        $ret = json_decode($result);
        if (isset($ret->errcode)) {
            $this->errorLog("获取体验小程序的体验二维码操作失败,appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        } else {
            return $result;
        }
    }

    /**
     * 提交审核
     * @params string $tag : 小程序标签，多个标签以空格分开
     * @params strint $title : 小程序页面标题，长度不超过32
     * */
    public function submitReview($tag = "商城", $title = "小程序开发")
    {
        $first_class = '';
        $second_class = '';
        $first_id = 0;
        $second_id = 0;
        $address = "pages/index/index";
        $order_path = "pages/order/index/index";
        $category = $this->getCategory();
        if (!empty($category)) {
            $first_class = $category[0]->first_class ?: '';
            $second_class = $category[0]->second_class ?: '';
            $first_id = $category[0]->first_id ?: 0;
            $second_id = $category[0]->second_id ?: 0;
        }
        $getpage = $this->getPage();
        if (!empty($getpage) && isset($getpage[0])) {
            $address = $getpage[0];
        }
        $url = "https://api.weixin.qq.com/wxa/submit_audit?access_token=" . $this->authorizer_access_token;
        $data = '{
                "item_list":[{
                    "address":"' . $address . '",
                    "tag":"' . $tag . '",
                    "title":"' . $title . '",
                    "first_class":"' . $first_class . '",
                    "second_class":"' . $second_class . '",
                    "first_id":"' . $first_id . '",
                    "second_id":"' . $second_id . '"
                }],
	            "order_path": "' . $order_path . '"
            }';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return $ret->auditid;
        } else {
            $this->errorLog("小程序提交审核操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 小程序审核撤回
     * 单个帐号每天审核撤回次数最多不超过1次，一个月不超过10次。
     * */
    public function unDoCodeAudit()
    {
        $url = "https://api.weixin.qq.com/wxa/undocodeaudit?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("小程序审核撤回操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 查询指定版本的审核状态
     * @params string $auditid : 提交审核时获得的审核id
     * */
    public function getAuditStatus($auditid)
    {
        $url = "https://api.weixin.qq.com/wxa/get_auditstatus?access_token=" . $this->authorizer_access_token;
        $data = '{"auditid":"' . $auditid . '"}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("查询指定版本的审核状态操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 查询最新一次提交的审核状态
     * */
    public function getLastAudit()
    {
        $url = "https://api.weixin.qq.com/wxa/get_latest_auditstatus?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("查询最新一次提交的审核状态操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 发布已通过审核的小程序
     * */
    public function release()
    {
        $url = "https://api.weixin.qq.com/wxa/release?access_token=" . $this->authorizer_access_token;
        $data = '{}';
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("发布已通过审核的小程序操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 获取授权小程序帐号的可选类目
     * */
    private function getCategory()
    {
        $url = "https://api.weixin.qq.com/wxa/get_category?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret->category_list;
        } else {
            $this->errorLog("获取授权小程序帐号的可选类目操作失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 获取小程序的第三方提交代码的页面配置
     * */
    private function getPage()
    {
        $url = "https://api.weixin.qq.com/wxa/get_page?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret->page_list;
        } else {
            $this->errorLog("获取小程序的第三方提交代码的页面配置失败，appid:" . $this->authorizer_appid . $ret->errmsg, $ret);
        }
    }

    /**
     * 创建小程序
     * @param $name
     * @param $code
     * @param $code_type
     * @param $legal_persona_wechat
     * @param $legal_persona_name
     * @param $component_phone
     * @return bool
     * @throws Open3rdException
     */
    public function fastCreate($name, $code, $code_type, $legal_persona_wechat, $legal_persona_name, $component_phone)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/component/fastregisterweapp?action=create&component_access_token=" . $this->thirdAccessToken;
        $data = json_encode([
            'name' => $name,
            'code' => $code,
            'code_type' => $code_type,
            'legal_persona_wechat' => $legal_persona_wechat,
            'legal_persona_name' => $legal_persona_name,
            'component_phone' => $component_phone
        ], JSON_UNESCAPED_UNICODE);
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("创建小程序失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 查询创建任务状态
     * @param $name
     * @param $legal_persona_wechat
     * @param $legal_persona_name
     * @return mixed
     * @throws Open3rdException
     */
    public function getFastCreate($name, $legal_persona_wechat, $legal_persona_name)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/component/fastregisterweapp?action=search&component_access_token=" . $this->thirdAccessToken;
        $data = json_encode([
            'name' => $name,
            'legal_persona_wechat' => $legal_persona_wechat,
            'legal_persona_name' => $legal_persona_name,
        ], JSON_UNESCAPED_UNICODE);
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("查询创建任务状态失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 获取提审限额
     * @return mixed
     * @throws Open3rdException
     */
    public function quota()
    {
        $url = "https://api.weixin.qq.com/wxa/queryquota?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("获取提审限额失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * @param $ownerSetting
     * @param $settingList
     * @param int $privacyVer  用户隐私保护指引的版本，1表示现网版本；2表示开发版。默认是2开发版
     * 1、开发版指的是通过setprivacysetting接口已经配置的用户隐私保护指引内容，但是还没发布到现网，还没正式生效的版本。
     * 2、现网版本指的是已经在小程序现网版本已经生效的用户隐私保护指引内容。
     * 3、如果小程序已有一个现网版，可以通过该接口（privacy_ver=1）直接修改owner_setting里除了ext_file_media_id之外的信息，修改后即可生效。
     * 4、如果需要修改其他信息，则只能修改开发版（privacy_ver=2），然后提交代码审核，审核通过之后发布生效。
     * 5、当该小程序还没有现网版的隐私保护指引时却传了privacy_ver=1，则会出现 86074 报错
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function setPrivacySetting($ownerSetting, $settingList, $privacyVer = 2){
        $url = "https://api.weixin.qq.com/cgi-bin/component/setprivacysetting?access_token=" . $this->authorizer_access_token;
        $data = [
            'privacy_ver' => $privacyVer,
            'owner_setting' => $ownerSetting, // 收集方（开发者）信息配置
            'setting_list' => $settingList, // 要收集的用户信息配置，可选择的用户信息类型参考下方详情。当privacy_ver传2或者不传时，setting_list是必填；当privacy_ver传1时，该参数不可传，否则会报错
        ];
//        if($privacyVer == 1){
//            unset($data['setting_list']);
//        }
        $ret = json_decode($this->getCurl()->post($url, json_encode($data, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("设置小程序用户隐私失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 查询小程序用户隐私保护
     * @param int $privacyVer  用户隐私保护指引的版本，1表示现网版本；2表示开发版。默认是2开发版
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function getPrivacySetting($privacyVer = 2){
        $url = "https://api.weixin.qq.com/cgi-bin/component/getprivacysetting?access_token=" . $this->authorizer_access_token;
        $data = json_encode(['privacy_ver' => $privacyVer], JSON_UNESCAPED_UNICODE);
        $ret = json_decode($this->getCurl()->post($url, $data)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("查询小程序用户隐私失败," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * 获取隐私接口检测结果
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function getPrivacyInfoRes(){
        $url = "https://api.weixin.qq.com/wxa/security/get_code_privacy_info?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("获取隐私接口检测结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/register-management/fast-regist-beta/registerBetaMiniprogram.html
     * 注册试用小程序
     * @param $arg
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function fastRegisterBetaweapp($arg){
        $url = "https://api.weixin.qq.com/wxa/component/fastregisterbetaweapp?access_token=" . $this->thirdAccessToken;
        $ret = json_decode($this->getCurl()->post($url, json_encode($arg, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("注册试用小程序结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/register-management/fast-regist-beta/verfifyBetaMiniprogram.html
     * 试用小程序快速认证
     * @param $verifyInfo
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function verifyBetaweapp($verifyInfo){
        $url = "https://api.weixin.qq.com/wxa/verifybetaweapp?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, json_encode(['verify_info' => $verifyInfo], JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("试用小程序快速认证结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/category-management/getAllCategories.html
     * 获取可设置的所有类目
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function getAllCategories(){
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/getallcategories?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->get($url)->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("获取可设置的所有类目结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/basic-info-management/setNickName.html
     * 设置小程序名称
     * @param $arg
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function setNickname($arg){
        $url = "https://api.weixin.qq.com/wxa/setnickname?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, json_encode($arg, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("设置小程序名称结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/basic-info-management/setSignature.html
     * 设置小程序介绍
     * @param $arg
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function modifySignature($arg){
        $url = "https://api.weixin.qq.com/cgi-bin/account/modifysignature?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, json_encode($arg, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("设置小程序介绍结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/basic-info-management/setHeadImage.html
     * 修改头像
     * @param $arg
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function modifyHeadImage($arg){
        $url = "https://api.weixin.qq.com/cgi-bin/account/modifyheadimage?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, json_encode($arg, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("修改头像结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/category-management/addCategory.html
     * 添加类目
     * @param $arg
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function addCategory($arg){
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/addcategory?access_token=" . $this->authorizer_access_token;
        $ret = json_decode($this->getCurl()->post($url, json_encode($arg, JSON_UNESCAPED_UNICODE))->response);
        if ($ret->errcode == 0) {
            return $ret;
        } else {
            $this->errorLog("添加类目结果," . $ret->errmsg . $ret->errcode, $ret);
        }
    }

    /**
     * https://developers.weixin.qq.com/doc/oplatform/openApi/OpenApiDoc/miniprogram-management/code-management/uploadMediaToCodeAudit.html
     * 上传提审素材
     * @param $picUrl
     * @param $type
     * @return mixed
     * @throws Open3rdException
     * @czs
     */
    public function uploadMedia($picUrl, $type = 1){
        $content = $this->getCurl()->get($picUrl);
        if($content->error_code != 0){
            throw new \Exception("素材无效");
        }
        $filename = md5($picUrl) . '.jpg';
        $path = \Yii::$app->basePath . '/web/temp/ext_app/';
        $localUrl = $path . $filename;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fp = fopen($localUrl, "a"); //将文件绑定到流
        fwrite($fp, $content->response); //写入文件
        fclose($fp);
        if($type == 1) {
            $url = "https://api.weixin.qq.com/wxa/uploadmedia?access_token=" . $this->authorizer_access_token;
            $res = $this->getClient()->post($url, [
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($localUrl, 'r'),
                    ],
                ],
            ]);
            $res = json_decode($res->getBody()->getContents(), true);
            if (isset($res['mediaid'])) {
                if (file_exists($localUrl)) {
                    unlink($localUrl);
                }
                return $res['mediaid'];
            } else {
                throw new \Exception(OpenErrorCode::errorMsg($res['errcode'], $res['errmsg']));
            }
        }else{
            $serve = new MediaService([
                'accessToken' => $this->authorizer_access_token
            ]);
            $res = $serve->upload(['type' => 'image', 'media' => $localUrl]);
            if (isset($res['errcode'])) {
                throw new \Exception(OpenErrorCode::errorMsg($res['errcode'], $res['errmsg']));
            } else {
                if (file_exists($localUrl)) {
                    unlink($localUrl);
                }
                return $res['media_id'];
            }
        }
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $curl;
    }

    private function errorLog($msg, $ret = '')
    {
        \Yii::error('==========error log=========');
        \Yii::error($msg);
        \Yii::error($ret);
        if (isset($ret->errcode)) {
            $msg = OpenErrorCode::errorMsg($ret->errcode, $msg);
        }
        throw new Open3rdException($msg, $ret->errcode);
    }

    private function getClient()
    {
        return new Client([
            'verify' => false,
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    }
}
