<?php
/**
 * @copyright ©2018 hook007
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */

namespace app\plugins\erp;

use app\handlers\HandlerBase;
use app\plugins\erp\forms\common\RequestForm;
use app\plugins\erp\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/erp', '基本设置'),
                'route' => 'plugin/erp/mall/config/index',
                'icon' => 'el-icon-setting',
            ]
        ];
    }

    public function getIndexRoute()
    {
        return 'plugin/erp/mall/config/index';
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'erp';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/erp', 'erp管理');
    }

    public function handler()
    {
        $register = new HandlerRegister();
        $HandlerClasses = $register->getHandlers();
        foreach ($HandlerClasses as $HandlerClass) {
            $handler = new $HandlerClass();
            if ($handler instanceof HandlerBase) {
                /** @var HandlerBase $handler */
                $handler->register();
            }
        }
        return $this;
    }

    public function notify($code){
        RequestForm::getInstance()->setAccessToken($code);
    }

    /**
     * @return string 获取插件的详细描述。
     */
    public function getContent()
    {
        return '';
    }
}
