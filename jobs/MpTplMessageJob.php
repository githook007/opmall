<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\jobs;

use app\forms\common\mptemplate\MpTplMessage;
use app\models\MpTemplateRecord;
use yii\queue\JobInterface;

class MpTplMessageJob extends BaseJob implements JobInterface
{
    public $mall;
    public $token;
    public $url;
    public $templateId;
    public $data;
    public $miniprogram;

    public $admin_open_list = [];
    public $app_id;
    public $app_secret;
    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        try {
            $this->setRequest();
            \Yii::$app->setMall($this->mall);
            $logs = [];
            $count = 0;

            foreach ($this->admin_open_list as $item) {
                //发送数据
                $args = [
                    'touser' => $item['open_id'],
                    'template_id' => $this->templateId,
                    'data' => $this->data,
                ];

                $log = [
                    'open_id' => $args['touser'],
                    'mall_id' => $this->mall->id,
                    'status' => 1,
                    'data' => \Yii::$app->serializer->encode($args),
                    'error' => '',
                    'created_at' => mysql_timestamp(),
                    'token' => $this->token,
                ];

                try {
                    $res = (new MpTplMessage())->senderMsg($args);
                } catch (\Exception $e) {
                    \Yii::error($e);
                    $log['error'] = $e->getMessage();
                    $log['status'] = 0;
                }
                $count++;
                $logs[] = $log;
            }

            if ($count > 0) {
                \Yii::$app->db->createCommand()->batchInsert(
                    MpTemplateRecord::tableName(),
                    ['open_id', 'mall_id', 'status', 'data', 'error', 'created_at', 'token'],
                    $logs
                )->execute();
            }
        } catch (\Exception $e) {
            \Yii::warning($e);
            throw $e;
        }
    }
}
