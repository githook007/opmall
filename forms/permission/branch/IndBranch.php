<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/19
 * Time: 10:37
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\permission\branch;

class IndBranch extends BaseBranch
{
    public $ignore = 'ind';

    public function deleteMenu($menu)
    {
        if (isset($menu['ignore']) && in_array($this->ignore, $menu['ignore'])) {
            return true;
        }
        return false;
    }

    public function logoutUrl()
    {
        return \Yii::$app->urlManager->createUrl('admin/index/index');
    }

    public function checkMallUser($user)
    {
        return parent::checkMallUser($user) && $user->mall_id > 0;
    }

    public function syncPublicPath()
    {
        $path = '/addons/op/';
        file_uri($path);
        return \Yii::$app->basePath . $path;
    }
}
