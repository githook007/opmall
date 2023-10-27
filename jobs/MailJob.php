<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2021/1/14
 * Time: 11:26 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;


use app\bootstrap\mail\SendMail;
use app\models\Mall;
use yii\queue\JobInterface;

class MailJob extends BaseJob implements JobInterface
{
    /**
     * @var SendMail $class
     */
    public $class;

    public $view;
    public $params;

    public function execute($queue)
    {
        $this->setRequest();
        $this->class->job($this->view, $this->params);
    }
}
