<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\miaosha\job;

use app\jobs\BaseJob;
use app\plugins\miaosha\forms\mall\activity\ActivityEditForm;
use yii\base\Component;
use yii\queue\JobInterface;

class MiaoshaActivityJob extends BaseJob implements JobInterface
{
    public $open_date;
    public $open_time;
    public $mall;
    /** @var  ActivityEditForm $miaoshaGoods */
    public $miaoshaGoods;
    public $user;

    public function execute($queue)
    {
        $this->setRequest();
        \Yii::$app->setMall($this->mall);
        \Yii::$app->user->setIdentity($this->user);
        $this->miaoshaGoods->executeSave();
        \Yii::warning('结束了');
    }
}