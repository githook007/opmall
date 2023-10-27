<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/4 18:20:00
 */

namespace app\forms\admin;

use Alchemy\Zippy\Zippy;
use app\forms\common\CommonOption;
use app\models\Option;
use GuzzleHttp\Client;

class UpdateForm
{
    static $object;
    private $projectName = "微购儿新版商城";
    private $requestUrl = "aHR0cHM6Ly91cGRhdGUubmV0YmNsb3VkLmNvbS9wdWJsaWMvaW5kZXgucGhwL2dldC9wcm9qZWN0"; // 正式
//    private $requestUrl = "aHR0cHM6Ly91cGRhdGUtZGV2LnhpbmhhbmtyLmNvbS9wdWJsaWMvaW5kZXgucGhwL2dldC9wcm9qZWN0"; // 测试
    private $cacheText = 'backUpdate_';

    /**
     * @return UpdateForm
     */
    public static function getInstance(){
        if(empty(self::$object)){
            self::$object = new self();
        }
        return self::$object;
    }

    public function getVersionData()
    {
        $version = app_version();
        $requestParams = ["version_number" => $version, "project_name" => $this->projectName, "domain" => \Yii::$app->request->hostInfo];
        $result = $this->curlRequest("post", @base64_decode($this->requestUrl), $requestParams);
        $res = json_decode(trim($result, chr(239) . chr(187) . chr(191)), true);
        if(!empty($res['data']['next_version'])){
            $nextVersion = $res['data']['next_version'];
            if($nextVersion['number'] > 1) {
                $pathInfo = pathinfo($nextVersion['path']);
                for ($i = 0; $i < $nextVersion['number']; $i++){
                    $nextVersion['pathList'][] = "{$pathInfo['dirname']}/{$pathInfo['filename']}.{$i}.{$pathInfo['extension']}";
                }
                $nextVersion['step'] = 0;
                $res['data']['next_version'] = $nextVersion;
            }
            \Yii::$app->cache->set($this->cacheText.\Yii::$app->session->getId(), $nextVersion, 7200);
        }
        return $res['data'];
    }

    public function update()
    {
        $versionData = \Yii::$app->cache->get($this->cacheText.\Yii::$app->session->getId());
        if (empty($versionData)) {
            $res = $this->getVersionData();
            if(empty($res['next_version'])) {
                throw new \Exception('已无新版本。');
            }else{
                $versionData = $res['next_version'];
            }
        }
        $version = $versionData['version_number'];
        if(!empty($versionData['pathList']) && isset($versionData['step'])){
            $tempFile = \Yii::$app->runtimePath . '/update-package/' . $version . "/src.{$versionData['step']}.zip";
            $this->download($versionData['pathList'][$versionData['step']], $tempFile);
            $versionData['step'] = $versionData['step'] + 1;
            if($versionData['step'] == count($versionData['pathList'])){
                $tempFile = \Yii::$app->runtimePath . '/update-package/' . $version . '/src.zip';
                $fp = fopen($tempFile, 'w+');
                if ($fp === false) {
                    throw new \Exception('无法保存文件，请检查文件写入权限。');
                }
                for ($i = 0; $i < $versionData['number']; $i ++){
                    $block_file = \Yii::$app->runtimePath . '/update-package/' . $version . "/src.{$i}.zip";
                    $handle = fopen($block_file, "rb");
                    fwrite($fp,fread($handle,filesize($block_file)));
                    fclose($handle);
                }
                fclose ($fp);
            }else{
                \Yii::$app->cache->set($this->cacheText.\Yii::$app->session->getId(), $versionData, 7200);
                return 2;
            }
        }else{
            $src = $versionData['path'];
            $tempFile = \Yii::$app->runtimePath . '/update-package/' . $version . '/src.zip';
            $this->download($src, $tempFile);
        }
        $zippy = Zippy::load();
        $archive = $zippy->open($tempFile);
        $archive->extract(\Yii::$app->basePath);
        $this->clearOpcache();
        unset($archive);

        $currentVersion = CommonOption::get(Option::NAME_VERSION);
        if (!$currentVersion) {
            $currentVersion = '0.0.0';
        }
        $lastVersion = $currentVersion;

        $versions = require \Yii::$app->basePath . '/versions.php';
        foreach ($versions as $v => $f) {
            $lastVersion = $v;
            if (version_compare($v, $currentVersion) > 0) {
                if ($f instanceof \Closure) {
                    try {
                        $f();
                    }catch (\Exception $e){}
                }
            }
        }
        \Yii::$app->cache->delete($this->cacheText.\Yii::$app->session->getId());
        CommonOption::set(Option::NAME_VERSION, $lastVersion);
        $this->delDir(\Yii::$app->runtimePath . '/update-package/' . $version);
        try {
            cmd_exe("cache/flush-schema", "yes");
        }catch (\Exception $e){}
        return 1;
    }

    function delDir($directory, $del = true)
    {
        if ($dir_handle = @opendir($directory)) {
            while ($filename = @readdir($dir_handle)) {
                if ($filename != '.' && $filename != '..') {
                    if (is_dir($directory . "/" . $filename)) {
                        self::delDir($directory . "/" . $filename, $del);
                    } else {
                        @unlink($directory . "/" . $filename);
                    }
                }
            }
            @closedir($dir_handle);
            if($del){
                @rmdir($directory);
            }
        }
    }

    private function clearOpcache()
    {
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }

    public function download($url, $file)
    {
        if (!is_dir(dirname($file))) {
            if (!make_dir(dirname($file))) {
                throw new \Exception('无法创建目录，请检查文件写入权限。');
            }
        }
        $fp = fopen($file, 'w+');
        if ($fp === false) {
            throw new \Exception('无法保存文件，请检查文件写入权限。');
        }

        $client = new Client([
            'verify' => false,
            'stream' => true,
        ]);
        $response = $client->get($url);
        $body = $response->getBody();
        while (!$body->eof()) {
            fwrite($fp, $body->read(1024));
        }
        fclose($fp);
        return $file;
    }

    private function curlRequest($method, $url, $data = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        if (strtolower($method) === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($data) {
                $data = is_string($data) ? $data : http_build_query($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        $content = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        if ($errno) {
            throw new \Exception($error);
        }
        return $content;
    }
}
