<?php

/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/2
 * Time: 15:32
 */

namespace app\plugins\aliapp\forms;

class TemplateInfo
{
    private $data;

    public function __construct($type, $info)
    {
        foreach ($info as $k => $v) {
            unset($info[$k]['color']);
        }

        switch ($type) {
            case "bargain_fail_tpl":
                $this->data =  [
                    'keyword1' => [
                        'value' => $info['keyword1']['value'],
                    ],
                    'keyword2' => [
                        'value' => $info['keyword2']['value'],
                    ],
                    'keyword3' => [
                        'value' => "最低价:" . $info['keyword3']['value'],
                    ],
                    'keyword4' => [
                        'value' => $info['keyword4']['value'],
                    ],
                ];
                break;

            default:
                $this->data =  $info;
                break;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
