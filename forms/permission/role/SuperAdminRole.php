<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/19
 * Time: 11:43
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\permission\role;

use app\forms\common\CommonAuth;
use app\models\UserIdentity;

class SuperAdminRole extends BaseRole
{
    public static $superAdmin;
    public $isSuperAdmin = true;

    public function getName()
    {
        return 'super_admin';
    }

    public function deleteRoleMenu($menu)
    {
        if (isset($menu['key']) && in_array($menu['key'], ['attachment'])) {
            return $this->special($menu);
        } else {
            return false;
        }
    }

    public function setPermission()
    {
        $this->permission = CommonAuth::getAllPermission();
    }

    public $showDetail = true;

    public function checkPlugin($plugin)
    {
        return true;
    }

    public function getNotPluginList()
    {
        // 2.获取本地插件代码列表
        try {
            $localSrcList = \Yii::$app->plugin->scanPluginList();
        } catch (\Exception $exception) {
            $localSrcList = [];
        }

        $pluginList = [];
        foreach ($localSrcList as $localItem) {
            $pluginList[] = [
                'name' => $localItem->getName(),
                'display_name' => $localItem->getDisplayName(),
                'pic_url' => $localItem->getIconUrl(),
            ];
        }

        // 4.获取数据库已安装插件列表
        $installedList = \Yii::$app->plugin->getList();

        // 5.排除掉数据库已安装的插件
        foreach ($installedList as $installedItem) {
            foreach ($pluginList as $i => $item) {
                if ($installedItem->name === $item['name']) {
                    unset($pluginList[$i]);
                    break;
                }
            }
        }
        return array_values($pluginList);
    }

    /**
     * @param $menu
     * @return bool
     * 特殊的菜单序要特殊处理
     */
    private function special($menu)
    {
        try {
            $mall = \Yii::$app->mall;
            if ($mall->user_id == 1) {
                return false;
            }
            $user = \Yii::$app->mall->user;
            $permission = json_decode($user->adminInfo->permissions, true);
            if (!in_array('attachment', $permission)) {
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return UserIdentity|null
     * 获取总管理员账号
     */
    public static function getSuperAdmin()
    {
        if (self::$superAdmin) {
            return self::$superAdmin;
        }
        self::$superAdmin = UserIdentity::findOne(['is_super_admin' => 1]);
        return self::$superAdmin;
    }

    public function getSecondaryPermission()
    {
        return CommonAuth::getSecondaryPermissionAll();
    }
}
