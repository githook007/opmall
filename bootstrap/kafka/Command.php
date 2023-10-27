<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\bootstrap\kafka;

use yii\queue\cli\Command as CliCommand;

/**
 * Manages application kafka-queue.
 *
 */
class Command extends CliCommand
{
    /**
     * @var Queue
     */
    public $queue;


    /**
     * @inheritdoc
     */
    protected function isWorkerAction($actionID)
    {
        return $actionID === 'listen';
    }

    /**
     * Listens kafka-queue and runs new jobs.
     * @param int $timeout number of seconds to wait a job.
     * It can be used as daemon process.
     */
    public function actionListen($timeout = 1)
    {
        $this->queue->listen($timeout);
    }
}
