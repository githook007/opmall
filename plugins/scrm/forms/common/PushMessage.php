<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/30
 * Time: 2:12 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\common;

use app\helpers\CurlHelper;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;
use app\plugins\scrm\models\ScrmConfig;

class PushMessage extends Model
{
    public static function push($method, $args)
    {
        $self = new self();
        return $self->$method($args);
    }

    /**
     * @var ScrmConfig $config
     */
    public $config;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config = ScrmConfig::findOne(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0]);
    }

    protected function getResult($res)
    {
        $msg = '';
        switch ($res['code']) {
            case 0:
                return $res;
            case 99:
                $msg = '接口请求失败';
                break;
            case 100:
                $msg = '请求方式错误';
                break;
            case 103:
                $msg = '参数错误';
                break;
            case 106:
                $msg = '数据不存在';
                break;
            case 201:
                $msg = '用户凭证无效或已过期';
                break;
            case 202:
                $msg = 'Appid或Secret错误';
                break;
            case 205:
                $msg = '无访问权限';
                break;
            default:
        }
        throw new ScrmException($msg . $res['msg']);
    }

    protected function token()
    {
        $key = 'scrm_push_message_mall_id_' . \Yii::$app->mall->id;
        if ($token = \Yii::$app->cache->get($key)) {
            return $token;
        }
        if (!$this->config) {
            throw new ScrmException('企业微信客户管理未配置，无法推送消息');
        }
        $api = sprintf('%s/open-api/get_token', $this->config->domain);
        $res = CurlHelper::getInstance()->httpPost($api, [], [
            'appid' => $this->config->appid,
            'secret' => $this->config->secret
        ]);
        if ($res['code'] != 0) {
            throw new ScrmException($res['msg']);
        }
        \Yii::$app->cache->set($key, $res['token'], $res['expire_time']);
        return $res['token'];
    }

    /**
     * @param $args
     * @return mixed
     * @throws ScrmException
     * 查询用户是否添加企业成员
     */
    protected function checkCustomer($args)
    {
        $api = sprintf('%s/open-api/mall/check_customer', $this->config->domain);
        $args['token'] = $this->token();
        return $this->getResult(CurlHelper::getInstance()->httpPost($api, [], $args));
    }

    /**
     * @param $args
     * @return mixed
     * @throws ScrmException
     * 查询用户是否绑定至scrm系统
     */
    protected function checkBind($args)
    {
        $api = sprintf('%s/open-api/mall/check_bind', $this->config->domain);
        $args['token'] = $this->token();
        return $this->getResult(CurlHelper::getInstance()->httpPost($api, [], $args));
    }

    /**
     * @param $args
     * @return mixed
     * @throws ScrmException
     * 绑定用户信息（推送）
     */
    protected function bindCustomer($args)
    {
        $api = sprintf('%s/open-api/mall/bind_customer', $this->config->domain);
        $args['token'] = $this->token();
        return $this->getResult(CurlHelper::getInstance()->httpPost($api, [], $args));
    }
}
