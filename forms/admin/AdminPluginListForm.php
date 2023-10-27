<?php

namespace app\forms\admin;

use app\bootstrap\response\ApiCode;
use app\forms\mall\plugin\PluginCatBaseForm;
use app\forms\permission\menu\MenusForm;
use app\plugins\Plugin;

class AdminPluginListForm extends PluginCatBaseForm
{
    public $cat_name;

    public function rules()
    {
        return [
            [['cat_name'], 'trim'],
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

                    $plugin['is_buy'] = true;
                    if (!\Yii::$app->role->checkPlugin($pluginObject)) {
                        $plugin['is_buy'] = false;
                    }
                    
                    // 未购买用户是否可见
                    // 默认不可见
                    if (!\Yii::$app->role->isSuperAdmin && !$plugin['is_buy'] && empty($plugin['appManage']['is_show'])) {
                        unset($cat['plugins'][$pIndex]);
                    }

                    $plugin['pic_url'] = $pluginObject->getIconUrl();
                    $plugin['route'] = '';

                    // 子账号显示总账号编辑的插件信息
                    if (!\Yii::$app->role->isSuperAdmin && isset($plugin['appManage'])) {
                        if ($plugin['appManage']['pic_url_type'] == 2) {
                            $plugin['pic_url'] = $plugin['appManage']['pic_url'];
                        }
                        $plugin['display_name'] = $plugin['appManage']['display_name'];
                        $plugin['desc'] = $plugin['appManage']['content'];
                    }
                } elseif (!\Yii::$app->role->isSuperAdmin) {
                    $plugin['is_buy'] = false; // 未安装的先不隐藏，引导客户联系购买
                    if (isset($plugin['appManage']) && empty($plugin['appManage']['is_show'])) { // 设置隐藏就去掉
                        unset($cat['plugins'][$pIndex]);
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
}
