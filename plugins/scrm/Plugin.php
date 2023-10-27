<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 10:23 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm;

use app\events\UserEvent;
use app\models\User;
use app\plugins\scrm\forms\common\PushMessage;

class Plugin extends \app\plugins\Plugin
{
    public function getName()
    {
        return 'scrm';
    }

    public function getDisplayName()
    {
        return \Yii::t('plugins/scrm', '企业微信客户管理');
    }

    public function getIndexRoute()
    {
        return 'plugin/scrm/mall/index/index';
    }

    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/scrm', '配置信息'),
                'route' => 'plugin/scrm/mall/index/index',
                'icon' => 'el-icon-star-on',
            ],
        ];
    }

    public function handler()
    {
        \Yii::$app->on(User::EVENT_LOGIN, function ($event) {
            if (!in_array($this->getName(), \Yii::$app->mall->role->permission)) {
                \Yii::error('没有scrm的权限，不进行事件处理');
                return true;
            }
            try {
                /** @var UserEvent $event */
                $res = PushMessage::push('bindCustomer', [
                    'user_id' => $event->user->id,
                    'unionid' => $event->user->unionid,
                    'nickname' => $event->user->nickname,
                    'mobile' => $event->user->mobile,
                    'avatar' => $event->user->userInfo->avatar,
                    'integral' => $event->user->userInfo->integral,
                    'balance' => $event->user->userInfo->balance,
                    'number_level' => $event->user->identity->member_level,
                    'parent_id' => $event->user->userInfo->parent_id,
                ]);
            } catch (\Exception $exception) {
                \Yii::error('scrm登陆事件异常补货');
                \Yii::error($exception);
            }
        });
    }

    public function install()
    {
        $sql = <<<EOF
CREATE TABLE `op_scrm_secret` ( `id` int(11) NOT NULL AUTO_INCREMENT, `app_key` varchar(255) NOT NULL DEFAULT '', `app_secret` varchar(255) NOT NULL DEFAULT '', `auth_key` varchar(128) NOT NULL, `created_at` timestamp NOT NULL, `updated_at` timestamp NOT NULL, `deleted_at` timestamp NOT NULL, `is_delete` tinyint(1) NOT NULL DEFAULT '0', `mall_id` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
CREATE TABLE `op_scrm_config` ( `id` int(11) NOT NULL AUTO_INCREMENT, `mall_id` int(11) NOT NULL, `appid` varchar(255) NOT NULL DEFAULT '', `secret` varchar(255) NOT NULL DEFAULT '', `domain` varchar(255) NOT NULL DEFAULT '', `is_delete` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOF;
        sql_execute($sql);
        parent::install();
    }
}
