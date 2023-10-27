<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: Jayi
 * 开票列表-》多状态
 */

namespace app\plugins\invoice\controllers\api;

use app\controllers\api\ApiController;
use app\controllers\api\filters\LoginFilter;
use app\plugins\invoice\forms\api\Index;

class IndexController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => ['invoicing', 'withdraw', 'invoice-list', 'send-email']
            ],
        ]);
    }

    /**
     * 申请开票-新增/编辑
     */
    public function actionInvoicing()
    {
        $form = new Index();
        return $form->invoicing(\Yii::$app->request->post());
    }

    /**
     * 撤销开票
     */
    public function actionWithdraw()
    {
        $form = new Index();
        return $form->withdraw(\Yii::$app->request->post());
    }

    /**
     * 开票列表
     */
    public function actionInvoiceList()
    {
        $form = new Index();
        return $form->getList(\Yii::$app->request->get());
    }

    /**
     * 发送邮箱
     */
    public function actionSendEmail()
    {
        $form = new Index();
        return $form->sendEmail(\Yii::$app->request->get());
    }
}
