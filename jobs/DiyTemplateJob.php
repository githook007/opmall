<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2021/1/7
 * Time: 4:14 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;

use app\models\Mall;
use app\models\Model;
use app\plugins\diy\forms\mall\market\Issue;
use app\plugins\diy\models\CloudTemplate;
use app\plugins\diy\models\CoreTemplate;
use yii\queue\JobInterface;

class DiyTemplateJob extends BaseJob implements JobInterface
{
    public $mall_id;

    public function execute($queue)
    {
        \Yii::warning('--DiyTemplateJob 一键安装模板--');
        try {
            $this->setRequest();
            $mall = Mall::findOne($this->mall_id);
            \Yii::$app->setMall($mall);
            \Yii::$app->user->setIdentity($mall->user);

            $templateList = CloudTemplate::findAll(['type' => 'diy']);

            foreach ($templateList as $template){
                $coreTemplate = CoreTemplate::findOne([
                    'template_id' => $template->id,
                    'is_delete' => 0
                ]);
                if ($coreTemplate && version_compare($coreTemplate->version, $template->version, '=')) {
                    continue;
                }

                try {
                    $issue = new Issue();
                    $issue->type = 'decode';
                    $list = $issue->decode($template->package);
                }catch (\Exception $e){
                    \Yii::error($e);
                    continue;
                }

                $coreTemplate = new CoreTemplate();
                $coreTemplate->is_delete = 0;
                $coreTemplate->template_id = $template->id;
                $coreTemplate->order_no = date('YmdHis');
                $coreTemplate->type = $template->type;
                $coreTemplate->name = $template->name;
                $coreTemplate->data = json_encode($list, JSON_UNESCAPED_UNICODE);
                $coreTemplate->price = $template->price;
                $coreTemplate->detail = $template->detail;
                $coreTemplate->version = $template->version;
                $coreTemplate->author = '系统';
                $coreTemplate->pics = $template->pics;
                if (!$coreTemplate->save()) {
                    throw new \Exception((new Model())->getErrorMsg($coreTemplate));
                }
            }
            return true;
        } catch (\Exception $exception) {
            \Yii::warning('--DiyTemplateJob error--');
            \Yii::error($exception);
        }
    }
}