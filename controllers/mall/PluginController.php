<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/17 14:47
 */


namespace app\controllers\mall;


use Alchemy\Zippy\Zippy;
use app\controllers\behaviors\SuperAdminFilter;
use app\bootstrap\response\ApiCode;
use app\forms\mall\plugin\PluginCatListForm;
use app\forms\mall\plugin\PluginCatSaveForm;
use app\forms\mall\plugin\PluginListForm;
use app\models\AppManage;
use app\plugins\Plugin;
use GuzzleHttp\Client;

class PluginController extends MallController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            SuperAdminFilter::class => [
                'class' => SuperAdminFilter::class,
                'safeActions' => ['index', 'detail', 'top-nav'],
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PluginListForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->search();
        } else {
            return $this->render('index');
        }
    }

    public function actionNotInstallList()
    {
        $pluginList = \Yii::$app->role->getNotPluginList();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $pluginList,
            ],
        ];
    }

    public function actionDetail($name)
    {
        if (\Yii::$app->request->isAjax) {
            $Class = '\\app\\plugins\\' . $name . '\\Plugin';
            if (!class_exists($Class)) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '插件不存在。',
                ];
            }
            /** @var Plugin $plugin */
            $plugin = new $Class();
            $data = [
                'id' => null,
                'name' => $plugin->getName(),
                'display_name' => $plugin->getDisplayName(),
                'pic_url' => $plugin->getIconUrl(),
                'content' => $plugin->getContent(),
                'type' => 'local',
                'version' => $plugin->getVersionFileContent(),
                'new_version' => false,
                'desc' => '',
            ];

            $data['installed_plugin'] = \Yii::$app->plugin->getInstalledPlugin($data['name']);

            $appManage = AppManage::findOne(['name' => $plugin->getName(), 'is_delete' => 0]);
            if ($appManage) {
                $data['display_name'] = $appManage->display_name;
                $data['desc'] = $appManage->content;
                $data['content'] = $appManage->detail;
                $data['pic_url'] = $appManage->pic_url_type == 1 ? $data['pic_url'] : $appManage->pic_url;
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $data,
            ];
        } else {
            return $this->render('detail');
        }
    }

    public function actionBuy($id)
    {
        try {
            throw new \Exception('功能未开放');
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '下单成功。',
                'data' => $data,
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }

    public function actionPay()
    {
    }

    public function actionDownload($id)
    {
        throw new \Exception('功能未开放');
        $name = $res['info']['name'];

        if (empty($res['package'])) {
            if (file_exists(\Yii::$app->basePath . '/plugins/' . $name . '/Plugin.php')) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '使用本地安装包。',
                ];
            }
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '插件安装包尚未发布',
            ];
        }

        $version = $res['package']['version'];
        $client = new Client([
            'verify' => false,
            'stream' => true,
        ]);
        $response = $client->get($res['package']['src_file']);
        $body = $response->getBody();
        $tempPath = \Yii::$app->runtimePath . '/plugin-package/' . $name . '/' . $version;
        if (!is_dir($tempPath)) {
            make_dir($tempPath);
        }
        $tempFile = $tempPath . '/src.zip';
        $fp = fopen($tempFile, 'w+');
        if ($fp === false) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '安装失败，请检查站点目录是否有写入权限。',
            ];
        }
        while (!$body->eof()) {
            fwrite($fp, $body->read(1024));
        }
        fclose($fp);
        if (!file_exists($tempFile)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '安装失败，请检查站点目录是否有写入权限。',
            ];
        }
        $pluginPath = \Yii::$app->basePath . '/plugins';
        if (!is_dir($pluginPath)) {
            if (!make_dir($pluginPath)) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '安装失败，请检查站点目录是否有写入权限。',
                ];
            }
        }
        try {
            $zippy = Zippy::load();
            $archive = $zippy->open($tempFile);
            $archive->extract($pluginPath);
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            $versionFile = $pluginPath . '/' . $name . '/version';
            if (file_put_contents($versionFile, $version) === false) {
                throw new \Exception('无法写入文件' . $versionFile . ',请检查目录写入权限。');
            }
        } catch (\Exception $exception) {
            unset($archive);
            unlink($tempFile);
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
        unset($archive);
        unlink($tempFile);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => 'success',
        ];
    }

    public function actionInstall($name)
    {
        try {
            $res = \Yii::$app->plugin->install($name);
            if ($res) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '安装成功。',
                ];
            } else {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '安装失败。',
                ];
            }
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }

    public function actionUninstall($name)
    {
        try {
            $res = \Yii::$app->plugin->uninstall($name);
            if ($res) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '卸载成功。',
                ];
            } else {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '卸载失败。',
                ];
            }
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }

    public function actionCatManager()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PluginCatListForm();
            $form->attributes = \Yii::$app->request->get();
            return $form->search();
        } else {
            return $this->render('cat-manager');
        }
    }

    public function actionSaveCat()
    {
        $form = new PluginCatSaveForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }

    public function actionTopNav(){
        if (\Yii::$app->request->isAjax) {
            $form = new PluginListForm();
            $form->attributes = \Yii::$app->request->post();
            return $form->topNav();
        }
    }
}
