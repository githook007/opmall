<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/10/27
 * Time: 6:12 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\lottery\forms\common;

use app\forms\common\template\order_pay_template\BaseInfo;

class LotteryInfo extends BaseInfo
{
    public const TPL_NAME = 'lottery_tpl';
    protected $key = 'lottery';
    protected $chineseName = '幸运抽奖--中奖结果通知';

    public function getSendClass()
    {
        return new LotteryTemplate();
    }

    public function configAll()
    {
        $iconUrlPrefix = './statics/img/mall/tplmsg/';
        return [
            'wxapp' => [
                'config' => [
                    'id' => '3217',
                    'keyword_id_list' => [
                        1,
                        5,
                        4,
                        3,
                    ],
                    'title' => '中奖结果通知',
                    'categoryId' => '307',
                    'type' => 2,
                    'data' => [
                        'thing1' => '',
                        'name5' => '',
                        'phrase4' => '',
                        'thing3' => '',
                    ]
                ],
                'local' => [
                    'name' => '中奖结果通知(类目: 服装/鞋/箱包 )',
                    'img_url' => $iconUrlPrefix . 'wxapp/gift_result_tpl.png',
                ]
            ],
            'aliapp' => [
                'local' => [
                    'name' => '抽奖活动结果通知(模板编号：AT0159)',
                    'img_url' => $iconUrlPrefix . 'aliapp/gift_result_tpl.png',
                ],
            ],
            'bdapp' => [
                'config' => [
                    'id' => 'BD1123',
                    'keyword_id_list' => [
                        13,
                        6,
                        11,
                        5,
                    ],
                    'title' => '中奖结果通知',
                ],
                'local' => [
                    'name' => '中奖结果通知(模板编号：BD1123)',
                    'img_url' => $iconUrlPrefix . 'bdapp/gift_result_tpl.png',
                ],
            ],
            'wechat' => [
                'config' => [
                    'id' => 'OPENTM412181311',
                    'keyword_id_list' => 'OPENTM412181311',
                    'title' => '抽奖结果通知',
                ],
                'local' => [
                    'name' => '抽奖结果通知（幸运抽奖）',
                    'img_url' => $iconUrlPrefix . 'wechat/gift_result_tpl.png',
                ],
            ],

        ];
    }
}
