<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2021/1/14
 * Time: 10:11 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;

use app\forms\common\message\MessageService;
use app\models\Mall;
use yii\queue\JobInterface;

class MessageServiceJob extends BaseJob implements JobInterface
{
    /**
     * @var MessageService $messageService
     */
    public $messageService;

    /**
     * @var Mall $mall
     */
    public $mall;

    public $appPlatform;

    public function execute($queue)
    {
        $this->setRequest();
        $mall = Mall::findOne($this->mall->id);
        \Yii::$app->setMall($mall);
        \Yii::$app->setAppPlatform($this->appPlatform);
        $this->messageService->job();
    }
}
