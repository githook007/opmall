<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\copyright;


use app\bootstrap\response\ApiCode;
use app\forms\common\CommonAppConfig;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class CopyrightForm extends Model
{
    public function getDetail()
    {
        $option = CommonAppConfig::getCoryRight();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $option,
            ]
        ];
    }

    public function getDefault()
    {
        return [
            'pic_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/statics/img/copyright_pic.png",
            'description' => '©2022微购儿提供技术支持',
            'link_url' => '',
            'link' => '',
            'status' => '1',
            'record_info' => ''
        ];
    }
}
