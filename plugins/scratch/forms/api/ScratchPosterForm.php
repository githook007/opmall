<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\scratch\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\common\grafika\GrafikaOption;

class ScratchPosterForm extends GrafikaOption
{

    private function getDefault()
    {
        return [
            'scratch' => [
                'bg_pic' => [
                    'url' => \Yii::$app->basePath . '/plugins/scratch/assets/img/scratch-qrcode.png',
                    'file_type' => 'other',
                ],
                'qr_code' => [
                    'is_show' => '1',
                    'size' => 400,
                    'top' => 616,
                    'left' => 175,
                    'type' => '2',
                    'file_path' => '',
                    'file_type' => 'image',
                ],
                'head' => [
                    'is_show' => '1',
                    'size' => 80,
                    'top' => 480,
                    'left' => 160,
                    'file_type' => 'image',
                ],
                'nickname' => [
                    'is_show' => '1',
                    'font' => 25,
                    'top' => 490,
                    'left' => 272,
                    'text' => \Yii::$app->user->identity->nickname,
                    'color' => '#ffffff',
                    'file_type' => 'text',
                ],
                'desc_a' => [
                    'is_show' => '1',
                    'font' => 25,
                    'top' => 530,
                    'left' => 272,
                    'text' => '邀请你一起刮大奖',
                    'color' => '#ffffff',
                    'file_type' => 'text',
                ],
                'desc_b' => [
                    'is_show' => '1',
                    'font' => 28,
                    'top' => 1064,
                    'left' => 270,
                    'text' => '扫描二维码',
                    'color' => '#ffffff',
                    'file_type' => 'text',
                ],
                'desc_c' => [
                    'is_show' => '1',
                    'font' => 30,
                    'top' => 1114,
                    'left' => 240,
                    'text' => '和我一起抽奖',
                    'color' => '#ffffff',
                    'file_type' => 'text',
                ],
            ]
        ];
    }

    public function poster()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $this->get()
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
        }
    }

    private function get()
    {
        $option = $this->getDefault()['scratch'];
        $cache = $this->getCache($option);
        if ($cache) {
            return ['pic_url' => $cache . '?v=' . time()];
        }

        isset($option['qr_code']) && $option['qr_code']['file_path'] = self::qrcode($option, [
            ['user_id' => \Yii::$app->user->id],
            240,
            'plugins/scratch/index/index'
        ], $this);
        isset($option['head']) && $option['head']['file_path'] = self::head($this);

        $editor = $this->getPoster($option);
        return ['pic_url' => $editor->qrcode_url . '?v=' . time()];
    }

}
