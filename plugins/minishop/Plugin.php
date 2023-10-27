<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/9
 * Time: 5:06 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop;

use app\handlers\HandlerBase;
use app\models\PaymentOrderUnion;
use app\plugins\minishop\forms\PaymentForm;
use app\plugins\minishop\handlers\HandlerRegister;

class Plugin extends \app\plugins\Plugin
{
    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/minishop', '基础设置'),
                'route' => 'plugin/minishop/mall/setting',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/minishop', '交易组件'),
                'route' => 'plugin/minishop/mall/index',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/minishop', '类目管理'),
                'route' => 'plugin/minishop/mall/cat',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/minishop', '品牌管理'),
                'route' => 'plugin/minishop/mall/brand',
                'icon' => 'el-icon-star-on',
            ],
        ];
    }

    public function getName()
    {
        return 'minishop';
    }

    public function getDisplayName()
    {
        return \Yii::t('plugins/minishop', '交易组件');
    }

    public function getIndexRoute()
    {
        return 'plugin/minishop/mall/setting';
    }

    public function handler()
    {
        $register = new HandlerRegister();
        $handlerClasses = $register->getHandlers();
        foreach ($handlerClasses as $HandlerClass) {
            $handler = new $HandlerClass();
            if ($handler instanceof HandlerBase) {
                $handler->register();
            }
        }
        return $this;
    }

    /**
     * @param $paymentOrderUnion
     * @return array
     */
    public function getPayment(PaymentOrderUnion $paymentOrderUnion)
    {
        $form = new PaymentForm();
        $form->paymentOrderUnion = $paymentOrderUnion;
        return $form->add();
    }
}
