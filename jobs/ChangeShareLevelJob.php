<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/5/28
 * Time: 17:04
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;


use app\forms\mall\statistics\InitDataForm;
use app\models\Mall;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ChangeShareLevelJob extends BaseJob implements JobInterface
{
    public $mall;

    public function execute($queue)
    {
        $this->setRequest();
        $mall = Mall::findOne($this->mall->id);
        \Yii::$app->setMall($mall);
        \Yii::error('--我进来啦--');
        $t = \Yii::$app->db->beginTransaction();
        try {
            $form = new InitDataForm();
            $form->share();
            $t->commit();
        } catch (\Exception $exception) {
            $t->rollBack();
            \Yii::error($exception);
        }
    }
}
