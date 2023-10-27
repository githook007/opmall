<?php
/**
 * @copyright ©2018 hook007
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:42
 */


namespace app\plugins\invoice;

use app\helpers\PluginHelper;
use app\plugins\invoice\models\Invoice;
use app\plugins\invoice\models\InvoiceSetting;

class Plugin extends \app\plugins\Plugin
{

    public function getMenus()
    {
        return [
            [
                'name' => \Yii::t('plugins/invoice', '基本配置'),
                'route' => 'plugin/invoice/mall/setting/index',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/invoice', '开票申请'),
                'route' => 'plugin/invoice/mall/applyOrder/index',
                'icon' => 'el-icon-star-on',
            ],
            [
                'name' => \Yii::t('plugins/invoice', '已开发票'),
                'route' => 'plugin/invoice/mall/applyOrder/success',
                'icon' => 'el-icon-star-on',
            ],
        ];
    }

    /**
     * 插件唯一id，小写英文开头，仅限小写英文、数字、下划线
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }

    /**
     * 插件显示名称
     * @return string
     */
    public function getDisplayName()
    {
        return \Yii::t('plugins/invoice', '发票管理');
    }

    public function getIndexRoute()
    {
        return 'invoice/invoice/mall/setting/index';
    }

    /**
     * 开关
     */
    public function getAppConfig()
    {
        $invoice = InvoiceSetting::findOne([
            'mall_id' => \Yii::$app->mall->id
        ]);
        if ($invoice){
            return $invoice->switch;
        }else{
            return 0;
        }

    }

    /**
     * 获取开票订单信息
     */
    public function getOrder($order_id){
        $invoice = Invoice::findOne(['order_id' => $order_id]);
        if ($invoice){
            $invoice->pdf_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$invoice->pdf_url;
            $invoice->pdf_img = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$invoice->pdf_img;
            $invoice->resubmit_time = date('Y-m-d H:i:s', $invoice->resubmit_time);
            $invoice->revoke_time = date('Y-m-d H:i:s', $invoice->revoke_time);
            $invoice->examine_time = date('Y-m-d H:i:s', $invoice->examine_time);
            $invoice->updated_time = date('Y-m-d H:i:s', $invoice->updated_time);
            $invoice->add_time = date('Y-m-d H:i:s', $invoice->add_time);
        }
        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @return mixed
     * 获取开票订单信息
     */
    public function getOrderObject($invoice){
        if ($invoice){
            $invoice->pdf_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$invoice->pdf_url;
            $invoice->pdf_img = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$invoice->pdf_img;
            $invoice->resubmit_time = date('Y-m-d H:i:s', $invoice->resubmit_time);
            $invoice->revoke_time = date('Y-m-d H:i:s', $invoice->revoke_time);
            $invoice->examine_time = date('Y-m-d H:i:s', $invoice->examine_time);
            $invoice->updated_time = date('Y-m-d H:i:s', $invoice->updated_time);
            $invoice->add_time = date('Y-m-d H:i:s', $invoice->add_time);
        }
        return $invoice;
    }

    public function getPickLink()
    {
        $iconBaseUrl = PluginHelper::getPluginBaseAssetsUrl($this->getName()) . '/img/pick-link';

        return [
            [
                'key' => 'invoice',
                'name' => \Yii::t('plugins/invoice', '发票管理'),
                'open_type' => '',
                'icon' => $iconBaseUrl . '/icon.png',
                'value' => '/plugins/invoice/index/index',
                'ignore' => [],
            ],
        ];
    }
}
