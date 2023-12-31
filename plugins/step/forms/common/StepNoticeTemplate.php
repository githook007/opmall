<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/12/25
 * Time: 10:23
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\step\forms\common;


use app\forms\common\template\tplmsg\BaseTemplate;
use app\plugins\step\forms\mall\TemplateForm;

/**
 * Class StepNoticeTemplate
 * @package app\plugins\step\forms\common
 * 签到提醒
 */
class StepNoticeTemplate extends BaseTemplate
{
    public $title;
    public $time;
    public $remark;
    protected $templateTpl = 'step_notice';

    /**
     * @return array
     * @throws \Exception
     * 每日签到提醒
     */
    public function msg()
    {
        return [
            'keyword1' => [
                'value' => $this->title,
                'color' => '#333333'
            ],
            'keyword2' => [
                'value' => $this->time,
                'color' => '#333333'
            ],
            'keyword3' => [
                'value' => $this->remark,
                'color' => '#333333'
            ]
        ];
    }

    public function test()
    {
        $this->title = '每日签到';
        $this->time = date('Y.m.d', time()) . ' 23:59:59';
        $this->remark = '亲，您今天还没有签到哦';
        return $this->send();
    }

    public function setTemplateForm()
    {
        $this->templateForm = new TemplateForm();
    }
}
