<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 4:35 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\common;

use app\models\Mall;
use app\plugins\scrm\forms\Model;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\models\ScrmSecret;

class Token extends Model
{
    public $app_key;
    public $app_secret;

    /**
     * @var Mall $mall
     */
    public $mall;

    /**
     * @var ScrmSecret $scrmSecret
     */
    public $scrmSecret;

    /**
     * @var int $cKeyLength
     * 动态密钥长度
     */
    public $cKeyLength = 4;
    /**
     * @var string $key
     * 加密的密钥
     */
    public $key = 'opmallkeji';

    /**
     * @var int $max_expire
     * 有效期
     */
    public $max_expire = 86400;

    /**
     * 校验app_key和app_secret
     * @throws ScrmException
     */
    public function validateKey()
    {
        $this->scrmSecret = ScrmSecret::findOne([
            'app_key' => $this->app_key,
            'is_delete' => 0
        ]);
        if (!$this->scrmSecret) {
            throw new ScrmException('无效的app_key');
        }
        if (!\Yii::$app->security->validatePassword($this->app_secret, $this->scrmSecret->app_secret)) {
            throw new ScrmException('无效的app_secret');
        }
    }

    /**
     * 获取加密过的access_token
     * @return string
     * @throws ScrmException
     * @throws \yii\base\Exception
     */
    public function accessToken()
    {
        $this->validateKey();
        $this->mall = Mall::findOne(['id' => $this->scrmSecret->mall_id]);
        \Yii::$app->setMall($this->mall);
        $this->delCache();
        $accessToken = \Yii::$app->security->generateRandomString();
        $key = $this->cacheKey($accessToken);
        $this->setCache($key);
        return $this->encode($accessToken . $this->mall->id);
    }

    /**
     * 清除之前app_key生成的缓存
     * 一个app_key只能生成一个缓存
     */
    public function delCache()
    {
        $key = \Yii::$app->redis->hget($this->app_key, 'access_token');
        if ($key) {
            \Yii::$app->redis->hdel($key, 'max_expire');
        }
    }

    /**
     * 设置access_token的缓存
     * @param $key
     */
    protected function setCache($key)
    {
        \Yii::$app->redis->hset($key, 'max_expire', time() + $this->max_expire);
        \Yii::$app->redis->hset($this->app_key, 'access_token', $key);
    }

    /**
     * 缓存的key值
     * @param $accessToken
     * @return string
     */
    protected function cacheKey($accessToken)
    {
        return 'scrm_' . $accessToken;
    }

    /**
     * 校验access_token是否正确
     * @throws ScrmException
     */
    public function validateAccessToken()
    {
        try {
            $scrmAccessToken = \Yii::$app->request->headers['Scrm-Access-Token'];
            if (!$scrmAccessToken) {
                throw new ScrmException('无效的access_token x01');
            }
            $accessToken = $this->getString($this->decode($scrmAccessToken));
            if (!$accessToken) {
                throw new ScrmException('无效的access_token x02');
            }
            $key = $this->cacheKey($accessToken);
            $time = \Yii::$app->redis->hget($key, 'max_expire');
            if ($time === false || $time <= time()) {
                throw new ScrmException('access_token已过期，请重新请求');
            }
            return true;
        } catch (\Exception $exception) {
            \Yii::$app->response->data = $this->failByException($exception);
            return false;
        }
    }

    /**
     * 从请求的token中拆解出真实的access_token和商城id
     * @param $decode
     * @return false|string
     */
    public function getString($decode)
    {
        $accessToken = substr($decode, 0, 32);
        $mallId = substr($decode, 32);
        $this->mall = Mall::findOne(['id' => $mallId]);
        \Yii::$app->setMall($this->mall);
        return $accessToken;
    }

    /**
     * 加密真实的access_token
     * @param $string
     * @return string
     */
    public function encode($string)
    {
        $keyC = substr(md5(microtime()), -$this->cKeyLength);
        $key = md5($this->key . $keyC);
        $keyLen = strlen($key);
        $newStr = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $k = $i % $keyLen;
            $newStr .= substr($string, $i, 1) ^ $key[$k];
        }
        return $keyC . base64_encode($newStr);
    }

    /**
     * 解密
     * @param $string
     * @return string
     */
    public function decode($string)
    {
        $key = md5($this->key . substr($string, 0, $this->cKeyLength));
        $string = base64_decode(substr($string, $this->cKeyLength));
        $keyLen = strlen($key);
        $newStr2 = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $k = $i % $keyLen;
            $newStr2 .= substr($string, $i, 1) ^ $key[$k];
        }
        return $newStr2;
    }
}
