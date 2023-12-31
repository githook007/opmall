<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/3
 * Time: 10:14
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bonus\models;


class TemplateForm extends \app\forms\common\template\TemplateForm
{
    protected function getDefault()
    {
        $iconUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl .
            '/statics/img/mall/tplmsg/';

        $newDefault = [
            [
                'name' => '成为队长通知',
                'bonus_become_captain' => '',
                'tpl_name' => 'bonus_become_captain',
                'img_url' => [
                    'wxapp' => $iconUrlPrefix . 'order_pay_tpl.png',
                ],
                'platform' => ['wxapp',],
                'tpl_number' => [
                    'wxapp' => '（模板编号：AT0674）',
                    'aliapp' => '（模板编号：BD0221）',
                ]
            ]
        ];

        return $newDefault;
    }

    protected function getTemplateInfo()
    {
        return [
            'wxapp' => [
                'bonus_become_captain' => [
                    'id' => 'AT0674',
                    'keyword_id_list' => [5, 6, 11, 4],
                    'title' => '审核状态通知'
                ],
            ]
        ];
    }
}
