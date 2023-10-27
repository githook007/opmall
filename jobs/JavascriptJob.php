<?php


namespace app\jobs;

use yii\queue\JobInterface;

class JavascriptJob extends BaseJob implements JobInterface
{
    public $name;

    public function execute($queue)
    {
        if(!$this->name){
            return;
        }
        $this->setRequest();
        try{
            cmd_exe($this->name);
        }catch (\Exception $e){
            \Yii::error($e);
        }
    }
}
