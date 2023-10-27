<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/7
 * Time: 9:39
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\jobs;


use app\jobs\BaseJob;
use app\models\OrderSubmitResult;
use app\plugins\community\forms\api\ApplyForm;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ApplyJob extends BaseJob implements JobInterface
{
    public $form;
    public $token;
    public $mall;
    public $user;
    public $appVersion;
    public $appPlatform;

    public function execute($queue)
    {
        $this->setRequest();
        \Yii::$app->setMall($this->mall);
        \Yii::$app->user->setIdentity($this->user);
        \Yii::$app->setAppVersion($this->appVersion);
        \Yii::$app->setAppPlatform($this->appPlatform);
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /* @var ApplyForm $form */
            $form = $this->form;
            $form->token = $this->token;
            $form->save();
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            \Yii::error($exception->getMessage());
            \Yii::error($exception);
            $orderSubmitResult = new OrderSubmitResult();
            $orderSubmitResult->token = $this->token;
            $orderSubmitResult->data = $exception->getMessage();
            $orderSubmitResult->save();
            throw $exception;
        }
    }
}
