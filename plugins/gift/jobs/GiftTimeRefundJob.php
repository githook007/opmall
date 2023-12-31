<?php

/**
 * @copyright ©2018 hook007
 * @author jack_guo
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 */


namespace app\plugins\gift\jobs;


use app\models\Mall;
use app\plugins\gift\forms\common\CommonGift;
use app\plugins\gift\models\GiftLog;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class GiftTimeRefundJob extends BaseObject implements JobInterface
{
    /** @var Mall $mall */
    public $mall;

    /** @var GiftLog $gift_log_info */
    public $gift_log_info;

    /***
     * @param Queue $queue
     * @return mixed|void
     * @throws \Exception
     */
    public function execute($queue)
    {
        \Yii::$app->setMall($this->mall);

        $t = \Yii::$app->db->beginTransaction();
        try {
            CommonGift::refundGift($this->gift_log_info);

            $t->commit();
        } catch (\Exception $e) {
            $t->rollBack();
            \Yii::error('礼物定时抽奖队列错误');
            \Yii::error($e->getMessage());
            \Yii::error($e);
            throw $e;
        }
    }
}
