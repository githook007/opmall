<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/27
 * Time: 12:57
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\check_in\jobs;


use app\jobs\BaseJob;
use app\models\Mall;
use app\models\User;
use app\plugins\check_in\forms\common\Common;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * @property Mall $mall
 * @property User $user
 */
class SignInJob extends BaseJob implements JobInterface
{
    public $mall;
    public $user;
    public $token;
    public $day;
    public $status;

    public function execute($queue)
    {
        $this->setRequest();
        $this->mall = Mall::findOne($this->mall->id);
        $this->user = User::findOne($this->user->id);
        \Yii::$app->setMall($this->mall);
        $common = Common::getCommon($this->mall);
        try {
            $config = $common->getConfig();
            if ($config->status == 0) {
                throw new \Exception('签到未开启');
            }
            $award = $common->getAward($this->status);
            $award->user = $this->user;
            $award->status = $this->status;
            $award->day = $this->day;
            $award->token = $this->token;
            $award->addSignIn();
        } catch (\Exception $exception) {
            $common->addQueueData($this->token, $exception->getMessage());
        }
    }
}
