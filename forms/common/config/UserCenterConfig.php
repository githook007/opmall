<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/3
 * Time: 16:57
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\config;

use app\forms\api\app_platform\Transform;
use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\forms\common\order\CommonOrder;
use app\forms\mall\user_center\UserCenterForm;
use app\forms\mall\user_center\v2\UserCenterEditForm;
use app\forms\PickLinkForm;
use app\helpers\Json;
use app\models\Mall;
use app\models\Model;
use app\models\Option;
use app\models\User;
use app\models\UserCenter;

/**
 * Class UserCenterConfig
 * @package app\forms\common\config
 * @property Mall $mall
 */
class UserCenterConfig extends Model
{
    public static $instance;
    public $mall;
    private $permissions;
    private $isCheckPermissions = false;
    public $config_version = 1;
    public $old = false; // 兼容旧版接口的参数

    /**
     * 小程序api处请求获得的用户配置实例
     * @var UserCenter $apiUserCenter
     */
    public $apiUserCenter;

    /**
     * @var \app\forms\common\config\v2\UserCenterConfig $newConfig
     */
    public $newConfig;

    public static function getInstance($mall = null)
    {
        if (!$mall) {
            $mall = \Yii::$app->mall;
        }
        if (self::$instance && self::$instance->mall = $mall) {
            return self::$instance;
        }
        $instance = new self();
        $instance->mall = $mall;
        $instance->newConfig = \app\forms\common\config\v2\UserCenterConfig::getInstance($mall);
        return $instance;
    }

    public function getEdit($id)
    {
        if ($id) {
            $model = UserCenter::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $id, 'is_delete' => 0]);
        } else {
            $model = null;
        }
        $data = $this->getData($model);
        if ($this->config_version == 2) {
            $res['data'] = $data;
        } else {
            $res = $data;
        }
        $res['id'] =  $model ? $model->id : '';
        $res['name'] = $model ? $model->name : '';
        return $res;
    }

    /**
     * @param UserCenter $model
     * @return array
     */
    public function getData($model)
    {
        if ($this->old) {
            $default = (new UserCenterForm())->getDefault();
            if (!$model) {
                return $default;
            } else {
                $option = \Yii::$app->serializer->decode($model->config);
                return $this->checkDefault($option, $default);
            }
        }
        if (!$model) {
            $this->setVersionConfig(2);
            return (new UserCenterEditForm())->getDefault();
        } elseif ($model->version == 2) {
            $this->setVersionConfig(2);
            return Json::decode($model->data);
        } else {
            $option = \Yii::$app->serializer->decode($model->config);
            return $this->checkDefault($option, (new UserCenterForm())->getDefault());
        }
    }

    public function setVersionConfig($val)
    {
        $this->config_version = $val;
    }

    /**
     * @return array
     * 获取小程序端的用户配置
     */
    public function getConfig()
    {
        /** @var UserCenter $model */
        $model = UserCenter::find()->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
            ->findInSet(\Yii::$app->appPlatform, 'platform')->one();
        $this->apiUserCenter = $model;
        return $this->getData($model);
    }

    /**
     * @return array|null
     * 获取数据库存储的用户中心数据
     */
    public function getSetting()
    {
        $userCenterDefault = (new UserCenterForm())->getDefault();
        $option = CommonOption::get(
            Option::NAME_USER_CENTER,
            \Yii::$app->mall->id,
            Option::GROUP_APP,
            $userCenterDefault
        );
        $option = $this->checkDefault($option, $userCenterDefault);
        if (isset($option['order_bar'])) {
            // 兼容旧数据  将待评价改为已完成
            foreach ($option['order_bar'] as $k => $item) {
                if ($item['name'] == '待评价') {
                    $option['order_bar'][$k]['name'] = '已完成';
                }
            }
        }
        return $option;
    }

    /**
     * @param array $data
     * @param array $default
     * @return array
     * 处理新增的默认数据
     */
    public function checkDefault($data, $default)
    {
        foreach ($default as $key => $item) {
            if (!isset($data[$key])) {
                $data[$key] = $item;
                continue;
            }
            if (is_array($item)) {
                $data[$key] = $this->checkDefault($data[$key], $item);
            }
        }
        return $data;
    }

    /**
     * @param array $option
     * @return array
     * 处理下account_bar账户信息
     */
    public function accountBar($option)
    {
        if (!isset($option['account_bar'])) {
            return $option;
        }
        $option['account_bar']['integral']['navigate_enabled'] = false;
        foreach ($option['account_bar'] as $key => &$item) {
            switch ($key) {
                case 'balance':
                    $item['page_url'] = '/page/balance/balance';
                    break;
                case 'integral':
                    $item['page_url'] = '';
                    $item['navigate_enabled'] = false;
                    if ($this->isCheckPermissions) {
                        $permissions = $this->getPermissions();
                        if (in_array('integral_mall', $permissions)) {
                            $item['page_url'] = '/plugins/integral_mall/index/index';
                            $item['navigate_enabled'] = true;
                        }
                    }
                    break;
                case 'coupon':
                    $item['page_url'] = '/page/coupon/index/index';
                    break;
                case 'card':
                    $item['page_url'] = '/page/card/index/index';
                    break;
            }
        }
        unset($item);
        return $option;
    }

    /**
     * @param array $option
     * @return array
     * 处理下order_bar订单信息
     */
    public function orderBar($option)
    {
        if (!isset($option['order_bar'])) {
            return $option;
        }

        $orderInfoCount = (new CommonOrder())->getOrderInfoCount();
        $arr = [];
        foreach ($option['order_bar'] as $k => $item) {
            $item['link_url'] = '/pages/order/index/index?status=' . ((int)$k + 1);
            if ((int)$k + 1 === 5) {
                $item['link_url'] = '/pages/order/refund/index';
            }
            $item['open_type'] = PickLinkForm::OPEN_TYPE_2;
            $item['text'] = $orderInfoCount[$k] ?: '';
            $item['num'] = $orderInfoCount[$k] ? intval($orderInfoCount[$k]) : 0;
            $arr[] = $item;
        }
        $option['order_bar'] = $arr;
        return $option;
    }

    /**
     * @param array $option
     * @return array
     * 处理菜单中的信息
     */
    public function menu($option)
    {
        if (!isset($option['menus'])) {
            return $option;
        }
        $rechargeSetting = CommonAppConfig::getRechargeSetting();
        $appAdmin = false; // 手机端管理
        $shareShow = false; // 是否显示分销入口
        $permissions = $this->getPermissions();
        $transform = Transform::getInstance();
        if (!\Yii::$app->user->isGuest && !empty($permissions)) {
            $appAdmin = true;
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            if ($user->identity->is_admin != 1 && $user->identity->is_super_admin != 1
                || empty(\Yii::$app->plugin->getInstalledPlugin('app_admin'))
                || !in_array('app_admin', $permissions)) {
                $appAdmin = false;
            }

            if (\Yii::$app->mall->getMallSettingOne('is_not_share_show') == 1
                && $user->identity->is_distributor != 1 || $user->identity->is_distributor == 1) {
                $shareShow = true;
            }
        }

        //剔除无权限入口
        $menu = [];
        foreach ($option['menus'] as $i => $v) {
            if (isset($v['key']) && !in_array($v['key'], $permissions)) {
                continue;
            }
            if ($v['open_type'] == 'app_admin' && !$appAdmin) {
                continue;
            }

            if (isset($v['link_url']) && $v['link_url'] == '/pages/balance/password' && ($rechargeSetting['is_pay_password'] == 0 || $rechargeSetting['status'] == 0)) {
                continue;
            }

            $menu[] = $v;
        }

        if (!$shareShow) {
            $transform->setNotSupport([
                'user_center' => [
                    '/pages/share/index/index'
                ],
            ]);
        }
        // 剔除插件中不支持的链接
        foreach ($permissions as $name) {
            try {
                $plugin = \Yii::$app->plugin->getPlugin($name);
                $transform->setNotSupport($plugin->getSpecialNotSupport());
            } catch (\Exception $exception) {
            }
        }
        $option['menus'] = $transform->transformUserCenter(array_values($menu));

        return $option;
    }

    public function member($option)
    {
        // 加载会员图标
        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->identity->member_level != 0) {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            $level = $user->identity->member;
            if ($level && $level->status == 1) {
                $option['member_pic_url'] = $level->pic_url;
                $option['member_bg_pic_url'] = $level->bg_pic_url;
            }
        }
        return $option;
    }

    /**
     * @param $option
     * @return mixed
     * 添加充值配置
     */
    public function rechargeSetting($option)
    {
        $option['recharge_setting'] = CommonAppConfig::getRechargeSetting();
        return $option;
    }

    public function setPermissions($val)
    {
        $this->permissions = $val;
    }

    /**
     * @return array
     * 获取商城所属账号的权限
     */
    public function getPermissions()
    {
        if (!$this->isCheckPermissions) {
            return [];
        }
        if ($this->permissions) {
            return $this->permissions;
        }
        $this->permissions = \Yii::$app->mall->role->permission;
        return $this->permissions;
    }

    public function setIsCheckPermissions($val)
    {
        $this->isCheckPermissions = $val;
    }

    /**
     * @return array|mixed|null
     * 为了方便，获取小程序端用户中心的数据
     */
    public function getApiUserCenter()
    {
        if (version_compare(\Yii::$app->appVersion, '4.4.8', '<')) {
            $this->old = true;
        }else{
            // 临时兼容mall系统，后期删除 @czs
            if (version_compare(\Yii::$app->appVersion, '4.4.57', '<=')) {
                $this->old = true;
            }
            if (in_array(\Yii::$app->appVersion, ['4.4.9', '4.4.8', '4.4.10'])){
                $this->old = false;
            }
        }
        $this->setIsCheckPermissions(true);
        $userCenter = $this->getConfig();
        if (!$this->old && $this->config_version == 2) {
            $this->newConfig->setIsCheckPermissions(true);
            return $this->newConfig->getApiUserCenter($userCenter);
        } else {
            $userCenter = $this->accountBar($userCenter);
            $userCenter = $this->menu($userCenter);
            $userCenter = $this->orderBar($userCenter);
            $userCenter = $this->member($userCenter);
            $userCenter = $this->rechargeSetting($userCenter);
        }
        return $userCenter;
    }

    public function memberData()
    {
        if (version_compare(\Yii::$app->appVersion, '4.4.8', '<')) {
            $this->old = true;
        }else{
            // 临时兼容mall系统，后期删除 @czs
            if (version_compare(\Yii::$app->appVersion, '4.4.57', '<=')) {
                $this->old = true;
            }
            if (in_array(\Yii::$app->appVersion, ['4.4.9', '4.4.8', '4.4.10'])){
                $this->old = false;
            }
        }
        $userCenter = $this->getConfig();
        if (!$this->old && $this->config_version == 2) {
            $userCenter = array_column($userCenter, 'data', 'id');
            if (!isset($userCenter['user'])) {
                $userCenter = array_column((new UserCenterEditForm())->getDefault(), 'data', 'id');
            }
            $data = [
                'general_user_text' => $userCenter['user']['general_user_text'],
                'member_pic_url' => $userCenter['user']['member_pic_url'],
                'member_bg_pic_url' => $userCenter['user']['member_bg_pic_url'],
            ];
            $data = $this->member($data);
        } else {
            $member = $this->member($userCenter);
            $data = [
                'general_user_text' => $member['general_user_text'],
                'member_pic_url' => $member['member_pic_url'],
                'member_bg_pic_url' => $member['member_bg_pic_url'],
            ];
        }
        return $data;
    }
}
