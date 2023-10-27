<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: chenzs
 * Date: 2019/12/2
 * Time: 13:36
 */

namespace app\plugins\minishop\jobs;

use app\models\Mall;
use app\plugins\minishop\forms\CheckForm;
use app\plugins\minishop\models\MinishopRefund;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class OrderRefundJob extends BaseObject implements JobInterface
{
    public $id;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = MinishopRefund::findOne([
                'id' => $this->id,
            ]);
            if (!$model) {
                throw new \Exception('交易组件的退款单不存在');
            }
            \Yii::$app->setMall(Mall::findOne($model->mall_id));
            \Yii::$app->user->setIdentity(\Yii::$app->mall->user);

            $form = new CheckForm();
            $plugin = $form->check();
            $shopService = $plugin->getShopService();
            $shopService->sale->acceptRefund(['aftersale_id' => $model->aftersale_id]);
            $model->status = 13;
            if (!$model->save()) {
                throw new \Exception(isset($model->errors) ? current($model->errors)[0] : '数据异常！');
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::error($e);
        }
    }
}
