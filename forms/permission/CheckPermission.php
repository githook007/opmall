<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/17
 * Time: 15:18
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\permission;


use app\models\Mall;
use app\models\Model;

/**
 * Class CheckPermission
 * @package app\forms\common\permission
 * @property Mall $mall
 */
class CheckPermission extends Model
{
    public static $instance;
    public $mall;
    public $permissionFlip;

    public static function getInstance($mall = null)
    {
        if (!$mall) {
            $mall = \Yii::$app->mall;
        }
        if (self::$instance && self::$instance->mall == $mall) {
            return self::$instance;
        }
        self::$instance = new self();
        self::$instance->mall = $mall;
        $permission = \Yii::$app->branch->childPermission($mall->user->adminInfo);
        if (empty(\Yii::$app->plugin->getInstalledPlugin('app_admin'))
            || !in_array('app_admin', $permission)
            || empty(\Yii::$app->user->identity->identity->is_admin)
            || \Yii::$app->user->identity->identity->is_admin != 1) {
            $permission = array_merge(array_diff($permission, array('app_admin')));
        }
        self::$instance->permissionFlip = array_flip($permission);
        return self::$instance;
    }

    // 权限校验
    public function check($toCheck)
    {
        if ($toCheck && isset($this->permissionFlip[$toCheck])) {
            return true;
        } else {
            return false;
        }
    }
}
