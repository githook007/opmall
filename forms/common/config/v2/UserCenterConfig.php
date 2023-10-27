<?php
/**
 * Created By PhpStorm
 * Date: 2021/7/5
 * Time: 2:35 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\config\v2;

use app\forms\PickLinkForm;
use app\forms\api\app_platform\Transform;
use app\forms\common\CommonAppConfig;
use app\forms\common\diy\DiyBannerForm;
use app\forms\common\diy\DiyGoodsForm;
use app\forms\common\order\CommonOrder;
use app\forms\mall\user_center\v2\UserCenterEditForm;
use app\models\Mall;
use app\models\Model;
use app\models\User;

class UserCenterConfig extends Model
{
    public static $instance;

    /**
     * @var Mall $mall
     */
    public $mall;
    private $permissions;
    private $isCheckPermissions = false;

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
        return $instance;
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

    public function getApiUserCenter($userCenter)
    {
        $default = (new UserCenterEditForm())->getDefault();
        $userCenter = CommonAppConfig::check($userCenter, $default);
        foreach ($userCenter as &$item) {
            $method = $item['id'];
            if (method_exists($this, $method)) {
                $item['data'] = $this->$method($item['data']);
            }
        }
        unset($item);

        foreach ($userCenter as &$item) {
            if ($item['id'] == 'goods') {
                $item['data'] = $this->goods($item['data'], true);
            }
        }
        unset($item);
        return $userCenter;
    }

    public function user($data)
    {
        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->identity->member_level != 0) {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            $level = $user->identity->member;
            if ($level && $level->status == 1) {
                $data['member_pic_url'] = $level->pic_url;
                $data['member_bg_pic_url'] = $level->bg_pic_url;
            }
        }
        $data['code'] = $this->isCheckPermissions && in_array('teller', $this->getPermissions()) ? $data['code'] : 0;
        return $data;
    }

    public function account($item)
    {
        $item['balance_page_url'] = '/page/balance/balance';
        $item['integer_page_url'] = '';
        $item['integer_navigate_enabled'] = false;
        if ($this->isCheckPermissions) {
            $permissions = $this->getPermissions();
            if (in_array('integral_mall', $permissions)) {
                $item['integer_page_url'] = '/plugins/integral_mall/index/index';
                $item['integer_navigate_enabled'] = true;
            }
        }
        $item['coupon_page_url'] = '/page/coupon/index/index';
        $item['card_page_url'] = '/page/card/index/index';
        $item['recharge_setting'] = CommonAppConfig::getRechargeSetting();
        return $item;
    }

    /**
     * @param array $option
     * @return array
     * 处理菜单中的信息
     */
    public function menu($option)
    {
        $rechargeSetting = CommonAppConfig::getRechargeSetting();
        $appAdmin = false; // 手机端管理
        $shareShow = false; // 是否显示分销入口
        $permissions = $this->getPermissions();
        if (!\Yii::$app->user->isGuest && !empty($permissions)) {
            $appAdmin = true;
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            if (
                $user->identity->is_admin != 1
                && $user->identity->is_super_admin != 1
                || empty(\Yii::$app->plugin->getInstalledPlugin('app_admin'))
                || !in_array('app_admin', $permissions)
            ) {
                $appAdmin = false;
            }

            if (
                \Yii::$app->mall->getMallSettingOne('is_not_share_show') == 1
                && $user->identity->is_distributor != 1 || $user->identity->is_distributor == 1
            ) {
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

        $transform = Transform::getInstance();
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
        $option['menus'] = $transform->checkOpenType($option['menus']);

        return $option;
    }

    /**
     * @param array $option
     * @return array
     * 处理下order_bar订单信息
     */
    public function order($option)
    {
        $orderInfoCount = (new CommonOrder())->getOrderInfoCount();
        $arr = [];
        $iconList = [
            $option['pay_icon'],
            $option['send_icon'],
            $option['confirm_icon'],
            $option['sale_icon'],
            $option['refund_icon'],
        ];
        foreach ($iconList as $k => $icon) {
            $item = [];
            $item['link_url'] = '/pages/order/index/index?status=' . ((int)$k + 1);
            $item['icon'] = $icon;
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

    public $diyGoodsIds = [];
    public $diyCats = [];
    public $diyGoods;
    public $diyCatsGoods;

    //商品
    public function goods(array $arr, $is_show = false)
    {
        $diyGoodsForm = new DiyGoodsForm();
        if ($is_show) {
            if (!$this->diyGoods) {
                $this->diyGoods = $diyGoodsForm->getGoodsById($this->diyGoodsIds);
            }
            if (!$this->diyCatsGoods) {
                $this->diyCatsGoods = $diyGoodsForm->getGoodsByCat($this->diyCats);
            }
            return $diyGoodsForm->getNewGoods($arr, $this->diyGoods, $this->diyCatsGoods);
        } else {
            $this->diyGoodsIds = array_merge($diyGoodsForm->getGoodsIds($arr), $this->diyGoodsIds);
            $this->diyCats = array_merge($diyGoodsForm->getCats($arr), $this->diyCats);
            return $arr;
        }
    }

    public function banner($arr)
    {
        return (new DiyBannerForm())->getNewBanner($arr);
    }

    public function rubik($arr)
    {
        foreach ($arr['list'] as $rIndex => $rItem) {
            try {
                if ($arr['style'] == 0 && isset($arr['a_height'])) {
                    $arr['list'][$rIndex]['height'] = 'calc(100% + 0px)';
                    $arr['height'] = $arr['a_height'];
                }

                if ($arr['style'] == 0 && !isset($arr['a_height'])) {
                    $arr['list'][$rIndex]['height'] = '750px';
                    $arr['height'] = '750px';
                }
            } catch (\Exception $exception) {
            }
            if (isset($rItem['link']['key']) && $rItem['link']['key'] && !$this->getPermission($rItem['link']['key'])) {
                $arr['list'][$rIndex]['link']['value'] = '';
                $arr['list'][$rIndex]['link']['new_link_url'] = '';
                continue;
            }
        }
        foreach ($arr['hotspot'] as $hIndex => $hItem) {
            if (isset($hItem['link']['key']) && $hItem['link']['key'] && !$this->getPermission($hItem['link']['key'])) {
                $arr['hotspot'][$hIndex]['link']['value'] = '';
                $arr['hotspot'][$hIndex]['link']['new_link_url'] = '';
                continue;
            }
        }
        return $arr;
    }

    //权限判断
    private function getPermission(string $key)
    {
        return array_search($key, $this->getPermissions()) !== false;
    }
}
