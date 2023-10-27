<?php

namespace app\forms\mall\plugin;

use app\bootstrap\response\ApiCode;
use app\forms\permission\menu\MenusForm;
use app\models\CorePlugin;
use app\models\PluginNav;
use app\plugins\Plugin;

class PluginListForm extends PluginCatBaseForm
{
    public $cat_name;
    public $name;
    public $type;

    public function rules()
    {
        return [
            [['cat_name'], 'trim'],
            [['name', 'type'], 'safe'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $this->getErrorMsg($this),
            ];
        }
        if ($this->cat_name) {
            $this->searchOtherPlugins = false;
            $this->catCondition = [
                'name' => $this->cat_name,
            ];
        }

        $this->baseSearch();
        if ($this->otherPlugins && count($this->otherPlugins)) {
            $this->cats[] = [
                'name' => 'other',
                'display_name' => '未分组',
                'plugins' => $this->otherPlugins,
            ];
        }
        $pluginNav = PluginNav::find()->where(['mall_id' => \Yii::$app->mall->id])->asArray()->all();
        foreach ($this->cats as $cIndex => &$cat) {
            foreach ($cat['plugins'] as $pIndex => &$plugin) {
                if (!\Yii::$app->role->isSuperAdmin && intval($plugin['is_delete']) === 1) {
                    unset($cat['plugins'][$pIndex]);
                }
                $plugin['show_detail'] = \Yii::$app->role->showDetail;
                $PluginClass = "app\\plugins\\{$plugin['name']}\\Plugin";
                if (class_exists($PluginClass)) {
                    /** @var Plugin $pluginObject */
                    $pluginObject = new $PluginClass();
                    if (!\Yii::$app->role->checkPlugin($pluginObject)) {
                        unset($cat['plugins'][$pIndex]);
                        continue; // @czs
                    }
                    $plugin['is_buy'] = true;
                    $plugin['pic_url'] = $pluginObject->getIconUrl();
                    $plugin['route'] = $this->getPluginIndexRoute($pluginObject); // @czs
                } elseif (!\Yii::$app->role->isSuperAdmin) {
                    $plugin['is_buy'] = false;
                    if (isset($plugin['appManage']) && empty($plugin['appManage']['is_show'])) { // 设置隐藏就去掉
                        unset($cat['plugins'][$pIndex]);
                        continue; // @czs
                    }
                }
                if($plugin['appManage']){
                    $plugin['display_name'] = $plugin['appManage']['display_name'];
                    $plugin['external_link'] = $plugin['appManage']['external_link'];
                    $plugin['desc'] = $plugin['appManage']['content'];
                    if ($plugin['appManage']['pic_url_type'] == 2) {
                        $plugin['pic_url'] = $plugin['appManage']['pic_url'];
                    }
                }
                $plugin['is_nav'] = 0;
                foreach ($pluginNav as $v){// 插件是否显示在顶部导航状态  <@jayi>
                    if ($v['plugin_name'] == $plugin['name']){
                        $plugin['is_nav'] = 1;
                    }
                }

            }
            if (empty($cat['plugins'])) {
                unset($this->cats[$cIndex]);
            }
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'cats' => $this->cats,
            ],
        ];
    }

    private function getPluginIndexRoute($plugin)
    {
        $form = new MenusForm();
        $form->isExist = true;
        $form->pluginObject = $plugin;
        $res = $form->getMenus('plugin');
        $jumpRoute = '';
        if (isset($res['menus']) && is_array($res['menus']) && count($res['menus'])) {
            $sign = true;
            foreach ($res['menus'] as $value) {
                if (isset($value['route']) && $value['route'] == $plugin->getIndexRoute()) {
                    $jumpRoute = isset($value['is_jump']) && $value['is_jump'] == 0 ? '' : $value['route'];
                    $sign = false;
                    break;
                }
            }
            if ($sign) {
                $jumpRoute = isset($res['menus'][0]['is_jump']) && $res['menus'][0]['is_jump'] == 0 ? '' : ($res['menus'][0]['route'] ?? '');
            }
        }

        return $jumpRoute;
    }

    public function topNav(){
        if (!$this->validate()) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $this->getErrorMsg($this),
            ];
        }
        if($this->type != 1 && $this->type != 2){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '类型有误',
            ];
        }
        $res = false;
        if($this->type == 1){
            $count = PluginNav::find()->where(['mall_id' => \Yii::$app->mall->id])->count();
            if($count >= 10){
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '系统最多添加10个导航，请先删除已有的导航',
                ];
            }
            $pluginNav = new PluginNav();
            $pluginNav->mall_id = \Yii::$app->mall->id;
            $pluginNav->plugin_name = $this->name;
            $pluginNav->add_time = mysql_timestamp();
            $res = $pluginNav->save();
        }elseif($this->type == 2){
            $res = PluginNav::deleteAll(['mall_id' => \Yii::$app->mall->id, 'plugin_name' => $this->name]);
        }
        if(!$res){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '失败',
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '成功',
        ];
    }

    public function getTopNav(){
        $query = PluginNav::find()->alias('a')
            ->leftJoin(['b' => CorePlugin::tableName()], 'a.plugin_name = b.name')
            ->where(['a.mall_id' => \Yii::$app->mall->id])
            ->select(['a.id', 'a.plugin_name', 'b.display_name', 'b.pic_url'])
            ->asArray()
            ->all();
        $data = [];
        foreach ($query as $v){
            $PluginClass = "app\\plugins\\{$v['plugin_name']}\\Plugin";
            if (class_exists($PluginClass)) {
                $pluginObject = new $PluginClass();
                $route = $this->getPluginIndexRoute($pluginObject);
                $data[] = [
                    'name' => $v['display_name'],
                    'new_window' => true,
                    'url' => \Yii::$app->request->scriptUrl . '?r=' . $route
                ];
            }
        }
        return $data;
    }
}
