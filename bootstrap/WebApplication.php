<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 12:00
 */

namespace app\bootstrap;

use app\helpers\Json;
use yii\base\InvalidConfigException;

/***
 * Class Application
 * @package app\bootstrap
 */
class WebApplication extends \yii\web\Application
{
    use Application;

    private $appIsRunning = true;

    /**
     * Application constructor.
     * @param null $config
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        $this->setInitParams()
            ->loadDotEnv()
            ->defineConstants();

        require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

        if (!$config) {
            $config = require __DIR__ . '/../config/web.php';
        }

        parent::__construct($config);

        $this->enableObjectResponse()
            ->enableErrorReporting()
            ->loadAppLogger()
            ->loadAppHandler()
            ->loadPluginsHandler();
    }

    public function setSessionMallId($id)
    {
        if (!is_numeric($id)) {
            return;
        }
        $key1 = md5('Mall_Id_Key_1_' . date('Ym'));
        $key2 = md5('Mall_Id_Key_2_' . date('Ym'));
        $value1 = base64_encode(\Yii::$app->security->encryptByPassword($id, 'key' . $key1));
        $value2 = base64_encode(\Yii::$app->security->encryptByPassword('0' . $id, 'key' . $key1));
        $this->getSession()->set($key1, $value1);
        $this->getSession()->set($key2, $value2);
    }

    public function getSessionMallId($defaultValue = null)
    {
        $key1 = md5('Mall_Id_Key_1_' . date('Ym'));
        $encodeDataBase64 = $this->getSession()->get($key1, null);
        if ($encodeDataBase64 === null) {
            return $defaultValue;
        }
        $encodeData = base64_decode($encodeDataBase64);
        if (!$encodeData) {
            return $defaultValue;
        }
        $value = \Yii::$app->security->decryptByPassword($encodeData, 'key' . $key1);
        if (!$value) {
            return $defaultValue;
        }
        return $value;
    }

    public function removeSessionMallId()
    {
        $key1 = md5('Mall_Id_Key_1_' . date('Ym'));
        $key2 = md5('Mall_Id_Key_2_' . date('Ym'));
        \Yii::$app->session->remove($key1);
        \Yii::$app->session->remove($key2);
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    // 因为数据迁移包含了特殊字符，导致图片显示不了。临时替换了特殊字符，后期可删除 @czs
    public function str2url($data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }
        $old = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
        $replace = dirname($old);
        $temp = str_replace(['DEFAULT_DOMAIN_NEW_MALL', 'DEFAULT_DOMAIN_MALL'], [$replace, $old], Json::encode($data));
        return Json::decode($temp);
    }
}
