<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/15
 * Time: 14:02
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\jobs;


use app\jobs\BaseJob;
use app\models\Mall;
use app\plugins\check_in\forms\common\Common;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ClearContinueJob extends BaseJob implements JobInterface
{
    public $mall;

    public function execute($queue)
    {
        try {
            \Yii::warning('执行清除计划');
            $this->setRequest();
            $this->mall = Mall::findOne($this->mall->id);
            \Yii::$app->setMall($this->mall);
            $common = Common::getCommon($this->mall);
            $config = $common->getConfig();
            $continueTypeClass = $common->getContinueTypeClass($config->continue_type);
            $count = $continueTypeClass->clearContinue();
            $continueTypeClass->setJob();
        } catch (\Exception $exception) {
            \Yii::warning('执行清除计划--失败');
            \Yii::error($exception->getMessage());
        }
    }
}
