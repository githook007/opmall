<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 2:30 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\mall;

use app\models\Model;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\models\ScrmConfig;
use app\plugins\scrm\models\ScrmSecret;
use app\plugins\wxapp\Plugin;

class IndexForm extends Model
{
    public function getDetail()
    {
        $model = ScrmSecret::findOne([
            'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
        ]);
        $appSecret = '';
        if (!$model) {
            $appSecret = \Yii::$app->security->generateRandomString();
            $model = $this->create($appSecret);
        }
        $config = ScrmConfig::findOne([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0
        ]);
        if (!$config) {
            $config = new ScrmConfig();
        }
        $wxAppid = '';
        try {
            /** @var Plugin $plugin */
            $plugin = \Yii::$app->plugin->getPlugin('wxapp');
            $wxAppid = $plugin->getWechat()->appId;
        } catch (\Exception $exception) {

        }
        return $this->success([
            'app_key' => $model->app_key,
            'app_secret' => $appSecret,
            'appid' => $config->appid,
            'secret' => $config->secret,
            'domain' => $config->domain,
            'mall_id' => \Yii::$app->mall->id,
            'wx_appid' => $wxAppid,
        ]);
    }

    protected function create($appSecret)
    {
        $model = new ScrmSecret();
        $model->app_key = 'scrm_' . \Yii::$app->security->generateRandomString(27);
        $model->app_secret = \Yii::$app->security->generatePasswordHash($appSecret, 5);
        $model->auth_key = \Yii::$app->security->generateRandomString();
        $model->mall_id = \Yii::$app->mall->id;
        $model->is_delete = 0;
        if (!$model->save()) {
            throw new ScrmException($this->getErrorMsg($model));
        }
        return $model;
    }

    public function reset()
    {
        $model = ScrmSecret::findOne([
            'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
        ]);
        $appSecret = \Yii::$app->security->generateRandomString();
        if (!$model) {
            $model = $this->create($appSecret);
        } else {
            $model->app_secret = \Yii::$app->security->generatePasswordHash($appSecret, 5);
            if (!$model->save()) {
                throw new ScrmException($this->getErrorMsg($model));
            }
        }
        return $this->success([
            'app_secret' => $appSecret
        ]);
    }
}
